<?php
class WeixinRestNAction extends Action{

	public $type       		= '';          //用户发来消息的类别
    public $content    		= '';          //用户发来的消息
    public $fromUser   		= '';          //用户微信账号
    public $toUser     		= '';          //本微信账号
    public $smallPicURL     = '';          //小图片图片的地址
    public $largePicURL     = '';          //大图片图片的地址
    public $dir        		= '';          //目录
    public $function_name   		= '';          //方法名称
    public $post       		= '';          //获取的POST信息

	public function _initialize(){
		import('ORG.Kungg.WeixinRest');
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
		$this->type = $wxrest->type;
        $this->content = $wxrest->content;
        $this->fromUser = $wxrest->fromUser;
        $this->toUser = $wxrest->toUser;
        $this->smallPicURL = $wxrest->smallURL;
        $this->largePicURL = $wxrest->largeURL;
		$wxrest->sendText('test',$wxrest->fromUser);
		
		return;

	}

	public function index(){
		$str = '_bind_help_test';
		preg_match_all('/_\w*?$/',$str,$str);
		dump($str);
		$str = preg_replace('/[^_]\w*$/','',$str);
		//$str = explode('.', $str,-2);
		dump($str);
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

	public function analyse(){
		if($this->type == 'text'){
			if($this->content == ''){
				return;
			}
			$wxmodel = D('WeixinRest');
			$cache = $wxmodel->analyse($this->fromUser);
			$dir_u = explode('.', $cache['dir']);
			$dir = C('WX.DIR');
			for($i = 0;$i < count($dir_u);$i++){
				$dir = $dir[$dir_u[$i]];
				$dir_function = $dir_function.$dir['function'];
			}
			/*	
				判断是否为跳转命令
				1.判断是否为一位数字
				2.判断该目录是否有子目录
				3.判断输入值的目录是否存在
			*/
			if(!preg_match('/^\d$/',$this->content) || $dir['content'] == '' || !array_key_exists($this->content,$dir)){
				$this->dir = $cache['dir'];
				$this->function_name = $dir_function;
				return;
			}
			//返回命令和进入命令
			if(!$this->content == '0'){
				$dir_u = $cache['dir'].'.'.$this->content;
				$dir_function = $dir_function.$dir[$this->content]['function'];
			}else{
				$dir_u = preg_replace('/\.\d$/','',$cache['dir']);
				$dir_function = preg_replace('', replacement, subject);
			}
			$dir_u = $dir[$this->content];
			$this->dir = $dir_u;
			$this->function_name = $dir_function;

		}
	}
}