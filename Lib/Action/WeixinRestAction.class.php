<?php
class WeixinRestAction extends Action{
    
    public function WeixinRestAction(){
        parent::__construct();
        import('ORG.Crypt.Crypt');
        import('ORG.Kungg.Weixin');
        import('ORG.Kungg.WX_Model');
        import('ORG.Kungg.Renren');
        
    }
    
    public function index(){
        $post  = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!$post){
            $this->wx_in();
            $wx_model = new WeixinModel();
            $wx_model->isConnect('$wx->fromUser');
            return;
        }
        $wx = new Weixin($post);
        if($wx->analyse == 'help'){
            $wx->send_text($wx->help);
            return;
        }
        if($wx->analyse == 'bind'){
            $wx_model = new WeixinModel();
            if($wx_model->isConnect($wx->fromUser)){
                $wx->send_text('亲，你已经绑定过了哦。');
                return;
            }
            $url = C('ROOT_URL').U('WeixinRest/bind');
            $str = '<a href="'.$url.'?wx_id='.$wx->fromUser.'">点击绑定</a>';
            $wx->send_text($str);
            return;
        }
        if($wx->analyse == 'show'){
            $wx_model = new WeixinModel();
            $data = $wx_model->getData($wx->fromUser);
            $renren = new Renren();
            $list = $renren->getPageList($data['access_token']);
            
            for($i = 0; $i < count($list); $i++){
                $msg = $msg.$list[$i]->name."\n id:".$list[$i]->page_id." \n ";
            }
            $msg = $msg."小伙伴，如果你的账号没有管理公共主页，也可以直接回复 #+内容 直接发状态哦！";
            $wx->send_text($msg);
            return;
        }
        if($wx->analyse == 'jcbd'){
            $wx_model = new WeixinModel();
            $wx_model->unbind($wx->fromUser);
            $wx->send_text('解绑成功');
            return;
        }
        if($wx->analyse == 'set_statue'){
            $wx_model = new WeixinModel();
            if(!$wx_model->isConnect($wx->fromUser)){
                $wx->send_text('亲，你的微信账号还没和我们绑定哦。输入bd绑定。');
                return;
            }
            $msg = explode('#',$wx->msg,2);  
            $page_id = trim($msg[0]);
            $statue = $msg[1]; 
            $user = new UserModel();
            $condition['weixin_id'] = $wx->fromUser;
            $data = $user->where($condition)->find();
            $renren = new Renren();
            $renren->setStatus($statue,$data['access_token'],$page_id);
            $wx->send_text("状态:\n".$statue."\n发送成功!");
            return;
        }
        if($wx->analyse == 'image'){
            $wx->send_text('图片发布功能稍后开放');
            return;
        }
        else{
            $wx->send_text($wx->help);
            return;
        }
        
        
        
        
    }
    
    
    /**
     * 微信验证网站是否有REST功能
     */
     
    public function wx_in(){
        $signature = $this->_get('signature');
        $timpstamp = $this->_get('timestamp');
        $nonce     = $this->_get('nonce');
        $echostr   = $this->_get('echostr');
        if(!$signature or !$timpstamp or !$nonce or $echostr){
            return;
        }
        $wx = new Weixin();
        if($wx->wx_in($timpstamp,$nonce,$signature)){
            return $echostr;
        }
        echo $echostr;
        return;
    }
    
    
    /**
     * 绑定账户
     */
     
    public function bind(){
        $wx_id = $this->_get('wx_id');
        $this->assign('wx_id',$wx_id);
        $this->display();
    }
    
    public function bind_log(){
        $wx_id = $this->_post('wx_id');
        $username = $this->_post('username');
        $password = $this->_post('password');
        if(!$wx_id){
            $wx_id = $this->_get('wx_id');
            $this->assign('wx_id',$wx_id);
            $this->display();
            return;
        }
        $password = sha1($password);
        $wx_model = new WeixinModel();
        if(!$wx_model->log($username,$password)){
            $this->error('账号或密码错误');
        }
        $wx_model->bind($username,$wx_id);
        $this->success('绑定成功',U('WeinxinRest/close'));
    }
    
    public function close(){
        $this->display();
    }
    
    public function test(){
        $post  = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!$post){
            $this->wx_in();
            $wx_model = new WeixinModel();
            $wx_model->isConnect('$wx->fromUser');
            return;
        }
        $wx = new Weixin($post);
        $wx->WeixinTest();
    }
}
?>