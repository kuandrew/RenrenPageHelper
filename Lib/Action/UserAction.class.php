<?php
class UserAction extends Action{
    
    
    public function UserAction(){
        parent::__construct();
        import('ORG.Crypt.Crypt');
        import('ORG.Kungg.Renren');
    }
    
    protected function isLog(){
        $username = cookie('username');
        $password = cookie('password');
        $id       = cookie('id');
        if(!$username or !$password){
            return;
        }
        $Crypt = new Crypt();
        $username = $Crypt->decrypt($username,C('Secret_Key'));
        $data = new UserModel();
        return $data->isLog($username,$password,$id);
        
    }
    
    /**
     * 修改密码
     */
    public function setPassword(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        $password_old = sha1($this->_post('password-old'));
        $password_new = sha1($this->_post('password-new'));
        $id = cookie('id');
        if(!$this->_post('password-old') or !$this->_post('password-new')){
            $this->error('还没有输密码呢亲',U('Index/detail'));
            return;
        }
        if($password_old <> cookie('password')){
            $this->error('输入的密码不对啊亲~~',U('Index/detail'));
            return;
        }
        $user = new UserModel();
        $user->passwordset($id,$password_new);
        cookie('password',$password_new);
        $this->success('密码修改成功',U('Index/detail'));
    }
    
    
    
    /**
     * 直接在网站注册，不走人人网
     */
    public function noaccessreg(){
        if($this->isLog()){
            U('Index');
        }
        $csrf = new CsrfAction();
        if ($csrf->isCsrf()){
            return;
        }
        $username = $this->_post('username');
        $password = $this->_post('password');
        $noaccess = $this->_post('noaccess');
        $email    = $this->_post('email');
        if(!$noaccess){
            U('Index');
        }
        if(!$username or !$password){
            echo '<script>alert("亲，还没有告诉我用户名和密码啊")</script>';
            echo '<script>history.go(-1)</script>';
            return;
        }
        $user = new UserModel();
        $result = $user->regnocaccess($username,$password,$email);
        if($result == 2){
            echo '<script>alert("该用户名已经被抢注了")</script>';
            echo '<script>history.go(-1)</script>';
            return;
        }
        $id = $result['id'];
        $crypt = new Crypt();
        $username = $crypt->encrypt($username,C('Secret_Key'));
        $password = sha1($password);
        cookie('username',$username);
        cookie('password',$password);
        cookie('id',$id);
        $this->success('注册成功，请绑定你的人人账号，',C('site_url').'index.php/Index/detail');
    }
    
    
    
    /**
     * 使用人人网接入注册
     */
    public function reg(){
        if ($this->isLog()){
            U('Index');
        }
        $csrf = new CsrfAction();
        if ($csrf->isCsrf()){
            return;
        }
        $username = $this->_post('username');
        $password = $this->_post('password');
        $renren_id = $this->_post('renren_id');
        $email = $this->_post('email');
        if (!$username or !$password or !$renren_id){
            echo '<script>alert("亲，还没有告诉我用户名和密码啊")</script>';
            echo '<script>history.go(-2)</script>';
            return;
        }
        $user = new UserModel();
        $id = $user->reg($username,$password,$renren_id,$email);
        if (!$id){
            $this->error('<script>alert("亲，don\'t be devil");</script>亲，don\'t be devil',C('site_url'));
            return;
        }
        $crypt = new Crypt();
        $username = $crypt->encrypt($username,C('Secret_Key'));
        $password = sha1($password);
        cookie('username',$username);
        cookie('password',$password);
        cookie('id',$id);
        $access_token = $user->getAccessToken($id);
        $renren = new Renren();
        $renren->regEmailHash($renren_id,$access_token,$email);
        $this->success('登录成功',C('site_url'));        
    }
    
    /**
     * 来自微信的注册信息
     */
    
    public function reg_wx(){
        $csrf = new CsrfAction();
        if ($csrf->isCsrf()){
            return;
        }
        $username = $this->_post('username');
        $password = $this->_post('password');
        $renren_id = $this->_post('renren_id');
        $wx_id = $this->_post('wx_id');
        $email = $this->_post('email');
        if (!$username or !$password or !$renren_id){
            echo '<script>alert("亲，还没有告诉我用户名和密码啊")</script>';
            echo '<script>history.go(-2)</script>';
            return;
        }
        $user = new UserModel();
        $id = $user->reg($username,$password,$renren_id,$email,$wx_id);
        if (!$id){
            $this->error('<script>alert("亲，don\'t be devil");</script>亲，don\'t be devil',C('site_url'));
            return;
        }
        $wxmodel = D('WeixinRest');
        $wxmodel->where(array('weixin_id' => $wx_id))->save(array('uid' => $id));
        $wxmodel->changeDir($wx_id,'',-2);
        $crypt = new Crypt();
        $username = $crypt->encrypt($username,C('Secret_Key'));
        $password = sha1($password);
        cookie('username',$username);
        cookie('password',$password);
        cookie('id',$id);
        $access_token = $user->getAccessToken($id);
        $renren = new Renren();
        $renren->regEmailHash($renren_id,$access_token,$email);
        $this->success('注册成功',U('WeixinRest/close'));
    }
}
?>