<?php
class RenrenRestAction extends Action{

    public $type       = '';          //用户发来消息的类别
    public $content    = '';          //用户发来的消息
    public $fromUser   = '';          //用户微信账号
    public $toUser     = '';          //本微信账号
    public $smallPicURL     = '';          //小图片图片的地址
    public $largePicURL     = '';          //大图片图片的地址
    public $dir        = '';          //目录
    public $dir_name   = '';          //木灵名称
    public $post       = '';          //获取的POST信息

    public function _initialize(){
        //parent::__construct();
        import('ORG.Kungg.RenrenRest');
        import('ORG.Kungg.Renren');
        if(!IS_POST){
            return;
        }
        $post = $this->_post();
        $rest = new RenrenRest($post['message']);
        if(strtoupper(sha1($this->sortSign($post['timestamp'],$post['nonce']))) <> $post['signature']){
            $rest->send_text('服务器开小差了，亲，你发来的信息我们没收到哦。',$this->fromUser);
            return;
        }
        $rmodel = D('Rrrest');
        $data = $rmodel->getDir($rest->fromUser);
        $this->type = $rest->type;
        $this->content = $rest->content;
        $this->fromUser = $rest->fromUser;
        $this->toUser = $rest->toUser;
        $this->smallPicURL = $rest->smallURL;
        $this->largePicURL = $rest->largeURL;
        if(trim($this->content) == '' && $this->type <> 'image'){
            $rest->send_text('你什么都没说呢。',$this->fromUser);
            return;
        }  //检测输入内容是否为空
        if(!preg_match('/[^\d-., ]/',$this->content) && (strlen($this->content) == 1) &&!$data['isNew']){
            $cache = $rmodel->changeDir($this->fromUser,$data['dir'],$this->content,$data['name']);
            $this->dir = $cache['dir'];
            $this->post = $post;
            $this->dir_name = $cache['name'];
            $this->$cache['name']();
            return;   
        }  //检测是不是页面跳转命令且不是新用户
        $this->dir = $data['dir'];
        $this->post = $post;
        $this->dir_name = $data['name'];
        $this->$data['name']();

        
    }

    public function test(){
        $this->_bind_bind_reg();
        
    }

    /*
    检测该人人ID是否与网站绑定
    返回0：未连接 返回1：已连接，已绑定人人开放平台
    */
    public function isBind($rid){
        $user = M('User');
        $user = $user->field('renren_id')->where(array('renren_id' => $rid))->find();
        if($user['renren_id']){
            return 1;
        }else{
            return 0;
        }
    }

    public function sortSign($timestamp,$nonce){
        $data[0] = C('RENREN_PUBLIC_TOKEN');
        $data[1] = $timestamp;
        $data[2] = $nonce;
        sort($data,SORT_STRING);
        $result = $data[0].$data[1].$data[2];
        return $result;
    }
    
    public function index(){
        
    }

    public function _menu(){
        $rest = new RenrenRest($this->post['message']);
        $data = D('Rrrest');
        $data = $data->getHelp($this->dir);
        $rest->send_text($data,$this->fromUser);
        return;
    }

    public function _help(){
        $rmodel = D('Rrrest');
        $data = $rmodel->getHelp($this->dir);
        $rest = new RenrenRest($this->post['message']);
        $rest->send_text($data,$this->fromUser);
        $result = $rmodel->changeDir($this->fromUser,$this->dir,0,$this->dir_name);
        return;
    }

    public function _publish(){
        $rmodel = D('Rrrest');
        $data = $rmodel->getHelp($this->dir);
        $rest = new RenrenRest($this->post['message']);
        $rest->send_text($data,$this->fromUser);
        return;
    }

    public function _publish_status(){
        $rest = new RenrenRest();
        $user = M('User');
        $cache = $user->where(array('renren_id' => $this->fromUser))->find();
        if(!$cache){
            $rest->send_text('还木有绑定呢，亲。',$this->fromUser);
            return;
        }
        $renrenapi = new Renren();
        $renren = $renrenapi->getPageList($cache['access_token']);
        if(!preg_match('/^[a-zA-Z]#.*$/',$this->content)){
            foreach ($renren as $key => $value) {
                $msg = $msg.'回复 “'.chr($key + 97).'#内容”：在'.$value->name."发布状态\n";
            }
            $rest->send_text($msg,$this->fromUser);
            return;
        }
        $content = explode('#',$this->content,2);
        $content[0] = strtolower($content[0]);
        $content[0] = ord($content[0]) -97;
        $result = $renrenapi->setPageStatus($content[1],$cache['access_token'],$renren[$content[0]]->page_id);
        if(@$result->error_msg){
            $msg = $result->error_msg;
        }
        $msg = '成功向“'.$renren[$content[0]]->name.'”发送信息';
        $rest->send_text($msg,$this->fromUser);
        return;
    }

    public function _publish_photo(){
        $rest = new RenrenRest();
        $user = M('User');
        $cache = $user->where(array('renren_id' => $this->fromUser))->find();
        if(!$cache){
            $rest->send_text('还木有绑定呢，亲。',$this->fromUser);
            return;
        }
        $renrenapi = new Renren();
        $renren = $renrenapi->getPageList($cache['access_token']);
        if($this->type == 'image'){
            import('ORG.Net.Http');
            $http = new Http();
            $http->curlDownload($this->largePicURL,'pub/renren/'.$this->fromUser.'.jpg');
            $msg = "图片上传成功！\n";
            foreach ($renren as $key => $value) {
                $msg = $msg.'回复 “'.chr($key + 97).'#照片描述” ：将图片发送至 “'.$value->name."”\n";
            }
            $msg = $msg.'不写描述则直接上传';
            $rest->send_text($msg,$this->fromUser);
            return;
        }
        if(!preg_match('/^[a-zA-Z]#.*$/',$this->content) || !file_exists('pub/renren/'.$this->fromUser.'.jpg')){
            $msg = '请先上传图片亲，如果已完成图片上传，请回复英文字母进行发送。';
            $rest->send_text($msg,$this->fromUser);
            return;
        }
        $content = explode('#',$this->content,2);
        $content[0] = strtolower($content[0]);
        $content[0] = ord($content[0]) -97;
        $result = $renrenapi->photoPost('pub/renren/'.$this->fromUser.'.jpg',$content[1],$cache['access_token'],$renren[$content[0]]->page_id,'');
        if(@$result->error_msg){
            $msg = $result->error_msg;
        }
        $msg = '成功向“'.$renren[$content[0]]->name.'”发送图片';
        $rest->send_text($msg,$this->fromUser);
        return;
    }

    public function _bind(){
        $rmodel = D('Rrrest');
        $data = $rmodel->getHelp($this->dir);
        $rest = new RenrenRest($this->post['message']);
        $rest->send_text($data,$this->fromUser);
        return;
    }

    public function _bind_unbind(){
        $rest = new RenrenRest($this->post['message']);
        $rest->send_text("解绑功能正在开发啦。。。。\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n亲，怎么能解绑呢？",$this->fromUser);
        $rrrest->changeDir($this->fromUser,$this->dir,0,$this->dir_name);
        return;
    }

    public function _bind_bind(){
        if($this->isBind($this->fromUser)){
            $rest = new RenrenRest($this->post['message']);
            $rest->send_text('亲，你已经绑定了哦',$this->fromUser);
            $rrrest = D('Rrrest');
            $rrrest->changeDir($this->fromUser,$this->dir,0,$this->dir_name);
            return;
        }
        $rmodel = D('Rrrest');
        $data = $rmodel->getHelp($this->dir);
        $rest = new RenrenRest($this->post['message']);
        $rest->send_text($data,$this->fromUser);
        return;
    }

    public function _bind_bind_reg(){
        $data = "<a href='%s' >点击我注册</a>";
        $url = 'https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/reg_rrrest').'?renren_id='.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE');
        $data = sprintf($data,$url);
        $rest = new RenrenRest($this->post['message']);
        $rrrest = D('Rrrest');
        $result = $rrrest->changeDir($this->fromUser,$this->dir,0,$this->dir_name);
        $rrrest->changeDir($this->fromUser,$result['dir'],0,$result['name']);
        $rest->send_news('注册','点击我注册','',$url,$this->fromUser);
        return;
    }

    public function _bind_bind_bind(){
        $rest = new RenrenRest($this->post['message']);
        if(substr_count($this->content,'#') < 1){
            $rmodel = D('Rrrest');
            $data = $rmodel->getHelp($this->dir);
            $rest->send_text($data,$this->fromUser);
            return;
        }
        $info = explode('#',$this->content,2);
        $user=M('User');
        $map['username&password&renren_id'] = array($info[0],sha1($info[1]),array('eq',0),'_multi'=>true);
        $data = $user->where($map)->find();
        if(!$data){
            $rest->send_text("绑定失败：\n1.用户名或密码错误\n2.该账号已经绑定其他人人用户\n格式：账号#密码",$this->fromUser);
            return;
        }
        $url = 'https://graph.renren.com/oauth/authorize?display=mobile&client_id='.C('RENREN_APP_KEY').'&redirect_uri='.C('ROOT_URL').U('Renren/bind_rrrest').'/'.base64_encode($info[0]).'?renren_id='.$this->fromUser.'&response_type=code&scope='.C('RENREN_SCOPE');
        $data = '<a href="%s">点击后开始绑定</a>';
        $data = sprintf($data,$url);
        $rest->send_news('绑定','点击我绑定','',$url,$this->fromUser);
        $rrrest = D('Rrrest');
        $rrrest->changeDir($this->fromUser,$this->dir,0,$this->dir_name);
        return;
    }

}
?>