<?php
class WeixinRestNAction extends Action{

	public $type       		= '';          //用户发来消息的类别
    public $content    		= '';          //用户发来的消息
    public $fromUser   		= '';          //用户微信账号
    public $toUser     		= '';          //本微信账号
    public $smallPicURL     = '';          //小图片图片的地址
    public $largePicURL     = '';          //大图片图片的地址
    public $dir        		= '';          //目录
    public $function_name   = '';          //方法名称
    public $dirLead         = ''; 		   //获取当前目录跳转菜单
    public $post       		= '';          //获取的POST信息

	public function _initialize(){
		import('ORG.Kungg.WeixinRest');
		import('ORG.Kungg.Renren');
		$get = $GLOBALS['HTTP_RAW_GET_DATA'];
		$post = $GLOBALS['HTTP_RAW_POST_DATA'];
		$xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
		//判断各种验证
		if(!$this->isLegal($get)){
			return;
		}
		//判断各种验证
		if($get['echostr']){

			echo $get['echostr'];
			return;
		}
		//初始化接收到的信息
		$wxrest = new WeixinRest($post);
		$this->post = $post;
		$this->type = $wxrest->type;
        $this->content = $wxrest->content;
        $this->fromUser = $wxrest->fromUser;
        $this->toUser = $wxrest->toUser;
        $this->smallPicURL = $wxrest->smallURL;
        $this->largePicURL = $wxrest->picURL;
        $this->analyse();
        $this->getLead();
		$exec = $this->function_name;
		if(!$exec){
			return;
		}
		//echo $exec;
		$this->$exec();	
		return;

	}

	public function index(){
		#$a = explode('#','1',2);
		#dump($a);
	}

	public function isLegal($data){
		if(!$data['signature']){
			return 1;
		}
		$signature = $data['signature'];
		$timestamp = $data['timestamp'];
		$nonce = $data['nonce'];
		$token = C('WX.TOKEN');
		$tmpArr = array($token, $timestamp, $nonce);
      		sort($tmpArr, SORT_STRING);
      		$tmpStr = implode( $tmpArr );
      		$tmpStr = sha1( $tmpStr );
      		if($tmpStr == $signature){
      			return 1;
      		}else{
      			return 0 ;
      		}
	}

	/*
	*分析获取到的信息，返回当前目录并入库
	*/
	public function analyse(){
		if($this->type == 'text'){
			if($this->content == ''){
				return;
			}
			$wxmodel = D('WeixinRest');
			$cache = $wxmodel->analyse($this->fromUser);
			$dir_past = $cache['dir']; //原来的控制器位置
			$dir_list = C('WX.DIR'); //目录列表
			$content = $this->content;	//用户输入的内容
			$dir_past_arr = explode('.',$dir_past); //原来控制器位置->数组化
			$dir_cache = $dir_list;
			for($i = 0;$i < count($dir_past_arr);$i++){
				$dir_cache = $dir_cache[$dir_past_arr[$i]];
				$dir_function = $dir_function.$dir_cache['function'];
			}
			//如果输入的是一位数字
			if(preg_match('/^\d$/',$this->content)){
				//如果原来在根目录
				if($dir_past == '0'){
					//如果输入数字对应的操作方法存在 且 输入内容不为0，那么进新目录
					if(array_key_exists((int)$content,$dir_list) and $content != '0'){
						$dir = (int)$content;
						$dir_function = $dir_list[(int)$content]['function'];
					}else{
						$dir = 0;
						$dir_function = '_menu';
					}
				//如果不在根目录
				}else{
					//如果输入的是0：返回上一级 否则进入
					if($content == '0'){
						if(strlen($dir_past) == 1){
							$dir = 0;
							$dir_function = '_menu';
						}else{
							$dir = preg_replace('/\.([^\.]*?)$/','',$dir_past);
							$dir_function = preg_replace('/_([^_]*?)$/', '', $dir_function);
						}
					//如果输入的不是0
					}else{
						if(array_key_exists((int)$content,$dir_cache)){
							$dir = $dir_past.'.'.$content;
							$dir_function = $dir_function.$dir_cache[(int)$content]['function'];
						}else $dir = $dir_past; 
					}
				}
				$this->dir = $dir;
				$this->function_name = $dir_function;
			//如果输入的不是数字
			}else{
				$this->dir = $dir_past;
				$this->function_name  = $dir_function;
			}

			$wxmodel->where(array('weixin_id' => $this->fromUser))->save(array('dir' => $this->dir));
			return;
		}
		if($this->type == 'image'){
			$wxmodel = D('WeixinRest');
			$cache = $wxmodel->analyse($this->fromUser);
			$dir_past = $cache['dir']; //原来的控制器位置
			$dir_past_arr = explode('.',$dir_past); //原来控制器位置->数组化
			$dir_list = C('WX.DIR'); //目录列表
			$dir_cache = $dir_list;
			for($i = 0;$i < count($dir_past_arr);$i++){
				$dir_cache = $dir_cache[$dir_past_arr[$i]];
				$dir_function = $dir_function.$dir_cache['function'];
			}
			$this->dir = $dir_past;
			$this->function_name = $dir_function;
			return;
		}
	}

	/*
	*获取跳转菜单
	*/
	public function getLead(){
		$dir_list = C('WX.DIR');
		if($this->dir != '0'){
			$dir_arr = explode('.',$this->dir); //目前所在位置
			$dir_cache = $dir_list;
			//获取当前位置所在的菜单信息
			for($i = 0;$i < count($dir_arr);$i++){
				$dir_cache = $dir_cache[$dir_arr[$i]];
			}
			foreach($dir_cache as $key => $value){
				if(is_numeric($key)){
					$lead = $lead.'回复'.$key.'：'.$dir_cache[$key]['name']."\n";
				}
			}
			$lead = $lead.'回复0：返回上级菜单';
			if($dir_cache['content'] != '')$lead = $dir_cache['content'];
		}else{
			foreach($dir_list as $key => $value){
				if(is_numeric($key)){
					$lead = $lead.'回复'.$key.'：'.$dir_list[$key]['name']."\n";
				}
			}
		}
		$this->dirLead = $lead;
	}

	public function _help(){
		$wxrest = new WeixinRest($this->post);
		$wxrest->sendText($this->dirLead,$this->fromUser);
	}

	public function _menu(){
		$wxrest = new WeixinRest($this->post);
		$wxrest->sendText($this->dirLead,$this->fromUser);
	}

	public function _publish(){
		$wxrest = new WeixinRest($this->post);
		if(D('WeixinRest')->isBind($this->fromUser) != 1){
			$wxrest->sendText('你的账号还没有绑定呢，请先绑定哦。',$this->fromUser);
			D('WeixinRest')->changeDir($this->fromUser,$this->dir,-1);
			return;
		}
		
		$wxrest->sendText($this->dirLead,$this->fromUser);
		return;
	}

	public function _publish_status(){
		$wxrest = new WeixinRest($this->post);
		$user = D('User')->getInfoByWX($this->fromUser);
		$renren = new Renren();
		$cache = $renren->getPageList($user['access_token']);
		if(count(explode('#',$this->content,2)) == 2){
			$info = explode('#',$this->content,2);
			$renren->setStatus($info[1],$user['access_token'],$cache[$info[0] - 1]->page_id);
			$wxrest->sendText('成功向“'.$cache[$info[0] - 1]->name.'”发送状态',$this->fromUser);
			return;
		}
		foreach($cache as $key => $value){
			$msg = $msg.sprintf($this->dirLead,$key+1,$value->name)."\n";
		}
		$msg = $msg."\n".'回复0：返回上级菜单';
		$wxrest->sendText($msg,$this->fromUser);

	}

	public function _publish_photo(){
		$wxrest = new WeixinRest($this->post);
		$user = D('User')->getInfoByWX($this->fromUser);
		$renren = new Renren();
		$cache = $renren->getPageList($user['access_token']);
		if(count(explode('#',$this->content,2)) == 2){
			$info = explode('#',$this->content,2);
			$result = $renren->photoPost('pub/renren/'.$this->fromUser.'.jpg',$info[1],$user['access_token'],$cache[$info[0] - 1]->page_id,'');
        	if(@$result->error_msg){
            	$msg = $result->error_msg;
            	$wxrest->sendText($msg,$this->fromUser);
            	return;
        	}
			$wxrest->sendText('成功向“'.$cache[$info[0] - 1]->name.'”发送图片',$this->fromUser);
			return;
		}
		if($this->type == 'image'){
			import('ORG.Net.Http');
            $http = new Http();
            $http->curlDownload($this->largePicURL,'pub/renren/'.$this->fromUser.'.jpg');
            $msg = "图片上传成功！\n";
            foreach ($cache as $key => $value) {
                $msg = $msg.'回复 “'.($key+1).'#照片描述” ：将图片发送至 “'.$value->name."”\n";
            }
            $msg = $msg.'不写描述则直接上传';
            $wxrest->sendText($msg,$this->fromUser);
            return;
		}
		$wxrest->sendText($this->dirLead,$this->fromUser);
		return;

	}

	public function _bind(){
		$wxrest = new WeixinRest($this->post);
		$wxrest->sendText($this->dirLead,$this->fromUser);
		return;
	}

	public function _bind_bind(){
		$wxmodel = D('WeixinRest');
		$isbind = $wxmodel->isBind($this->fromUser);
		$wxrest = new WeixinRest($this->post);
		if($isbind > 0){
			$wxrest->sendText("你已经完成绑定了！",$this->fromUser);
			$this->dir = $wxmodel->changeDir($this->fromUser,$this->dir,-1);
			return;
		}
		else{
			$wxrest->sendText($this->dirLead,$this->fromUser);
			return;
		}
		//绑定了网站，但没绑定人人
		/*
		if($wxmodel == -1){
			$msg = $this->dirLead;$url = '"https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/reg_wx').'/'.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE').'"';
			$msg = preg_replace('/""/',$url, $msg);
			$wxrest->sendText($msg,$this->fromUser);
		//没有绑定网站和人人
		}elseif($wxmodel == 0){
			$msg = $this->dirLead;
			$url = '"https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/reg_wx').'/'.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE').'"';
			$msg = preg_replace('/""/',$url, $msg);
			$wxrest->sendText($msg,$this->fromUser);
		}else{
			$wxrest->sendText("你已经完成绑定了\n回复0：返回",$this->fromUser);
			return;
		}
		*/
	}

	public function _bind_unbind(){
		$wxrest = new WeixinRest($this->post);
		$wxrest->sendText('亲，解绑功能正在开发中！',$this->fromUser);
		return;
	}

	public function _bind_bind_bind(){
		$wxrest = new WeixinRest($this->post);
		//判断是否输入了用户名和密码
		if(count(explode('#',$this->content,2)) == 2){
			$cache = explode('#',$this->content,2);
			$map['username'] = $cache[0];
			$map['password'] = sha1($cache[1]);

			$user = M('user')->where($map)->find();
			//var_dump(M('user'));
			//判断用户名和密码是否正确
			if(!$user){
				$wxrest->sendText('用户名或密码错误。',$this->fromUser);
				return;
			}
			$wxmodel = D('WeixinRest');
			$isbind = $wxmodel->isBind($this->fromUser);
			$msg = '<a href="">点击我绑定</a>';
			/*
			*是否绑定 
			*0 没有绑定网站和人人
			*-1 绑定了网站但是没绑定人人
			*-2 没绑定网站，但绑定了人人
			*1 绑定了网站和人人
			*/
			if($isbind == 0){
				M('user')->where($cache)->save(array('weixin_id' => $this->fromUser));
				M('wxrest')->where(array('weixin_id' => $this->fromUser))->save(array('uid' => $cache['id']));
				$url = '"https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/bind_wx').'/'.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE').'"';
				$msg = preg_replace('/""/',$url, $msg);
				$wxrest->sendText($msg."\n回复0：返回",$this->fromUser);
				return;
			}elseif($isbind == -1){
				$url = '"https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/bind_wx').'/'.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE').'"';
				$msg = preg_replace('/""/',$url, $msg);
				$wxrest->sendText($msg."\n回复0：返回",$this->fromUser);
				return;
			}elseif($isbind == -2){
				M('user')->where($cache)->save(array('weixin_id' => $this->fromUser));
				M('wxrest')->where(array('weixin_id' => $this->fromUser))->save(array('uid' => $cache['id']));
				$wxrest->sendText('绑定成功',$this->fromUser);
				D('WeixinRest')->changeDir($this->fromUser,$this->dir,-2);
				return;
			}
		}
		$wxrest->sendText($this->dirLead,$this->fromUser);
	}

	public function _bind_bind_reg(){
		$wxrest = new WeixinRest($this->post);
		$msg = $this->dirLead;
		$url = '"https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/reg_wx').'/'.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE').'"';
		$msg = preg_replace('/""/',$url, $msg);
		$wxrest->sendText($msg."\n回复0：返回",$this->fromUser);
	}

}