<?php
class RenrenAction extends Action{
    public function RenrenAction(){
        parent::__construct();
        header("Content-type: text/html; charset=utf-8"); 
        import('ORG.Kungg.Renren');
        import('ORG.Crypt.Crypt');
    }
    
    public function index(){
        $code = $this->_get('code');
        if (!$code){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $renren = new Renren();
        $result = $renren->getAccessToken($code);
        if(!@$result->user){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $user = new UserModel();
        if(!$user->refreshAccessToken($result->user->id,$result->access_token,$result->refresh_token)){
            $data['renren_name'] = $result->user->name;
            $data['renren_id'] = $result->user->id;
            $data['username'] = $data['renren_name'];
            $this->assign($data);
            $this->display('reg');
            return;
        }
        $data = $user->getPassword($result->user->id);
        $crypt = new Crypt();
        cookie('username',$crypt->encrypt($data['username'],C('Secret_Key')));
        cookie('password',$data['password']);
        cookie('id',$data['id']);
        $this->success('登录成功',C('site_url'));
    }
    
    /**
     * 注册了却没绑定人人用户，使用下面方法来绑定
     */
    
    public function reg_twice(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        $code = $this->_get('code');
        if (!$code){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $renren = new Renren();
        $result = $renren->getAccessToken_twice($code);
        if(!@$result->user){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $user = new UserModel();
        $user->reg_twice(cookie('id'),$result->access_token,$result->refresh_token,$result->user->id);
        $id = cookie('id');
        $email = $user->getEmail($id);
        $renren->regEmailHash($result->user->id,$result->access_token,$email);
        $this->success('绑定成功',U('Index/detail'));
        return;
    }

    
    /**
     * 通过微信接口注册
     */
    public function reg_wx(){
        $code = $this->_param('code');
        $wx_id = $this->_param(2);
        if (!$code){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $renren = new Renren();
        $result = $renren->getAccessToken_wx($code,$wx_id);
        if(!@$result->user){
            $this->error('没有得到人人网的反馈',C('SITE_URL'));
            return;
        }
        $user = new UserModel();
        if(!$user->refreshAccessToken($result->user->id,$result->access_token,$result->refresh_token)){
            $data['renren_name'] = $result->user->name;
            $data['renren_id'] = $result->user->id;
            $data['username'] = $data['renren_name'];
            $data['wx_id'] = $wx_id;
            $this->assign($data);
            $this->display();
            return;
        }
        $data = $user->getPassword($result->user->id);
        $crypt = new Crypt();
        cookie('username',$crypt->encrypt($data['username'],C('Secret_Key')));
        cookie('password',$data['password']);
        cookie('id',$data['id']);
        $this->success('登录成功',C('site_url'));
    }


    /*
    从人人公众号注册
    */
    public function reg_rrrest(){
        if(IS_POST){
            $post = $this->_post();
            if(!$post['password']){
                $post['message'] = '密码还没有输哦';
                $this->assign('cache',$post);
                $this->display();
                return;
            }
            $post['password'] = sha1($post['password']);
            $post['createtime'] = time();
            $user = M('User');
            if($user->where(array('username' => $post['username']))->find()){
                echo '该用户名已经被注册了，请退出重试。';
                return;
            }
            $user->add($post);
            echo '注册成功';
            return;
        }
        $get = $this->_get();
        if(!$get['renren_id'] && !$get['code'] && count($get['_URL_']) < 2){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        $code = $get['code'];
        $renren_id = $get['renren_id'];
        $url = U($get['_URL_'][0].'/'.$get['_URL_'][1]);
        $renren = new Renren();
        $data = $renren->getAccessToken($code,$url,array('renren_id' => $renren_id));
        if(@$data->error){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        $this->assign('data',$data);
        $this->display();
    }

    public function bind_wx(){
        $get = $this->_get();
        if(!$get['code'] or count($get['_URL_']) < 3){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        $wxid = $get['_URL_'][2];
        $code = $get['code'];
        $url = U($get['_URL_'][0].'/'.$get['_URL_'][1]).'/'.$get['_URL_'][2];
        $renren = new Renren();
        $data = $renren->getAccessToken($code,$url);
        if(@$data->error){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        M('user')->where(array('weixin_id' => $wxid))->data(array('isConnect' => 1,'access_token' => $data->access_token,'refresh_token' => $data->refresh_token,'renren_id' => $data->user->id))->save();
        $user = M('user')->where(array('weixin_id' => $wxid))->find();
        D('WeixinRest')->where(array('weixin_id' => $user['weixin_id']))->save(array('uid' => $user['id']));
        D('WeixinRest')->changeDir($wxid,'',-2);
        echo '绑定成功';
        return;
    }


    /*
    从人人公众号来的绑定
    */
    public function bind_rrrest(){
        $get = $this->_get();
        if(!$get['renren_id'] && !$get['code'] && count($get['_URL_']) < 3){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        $username = $get['_URL_'][2];
        $username = base64_decode($username);
        $renren_id = $get['renren_id'];
        $code = $get['code'];
        $url = U($get['_URL_'][0].'/'.$get['_URL_'][1]).'/'.$get['_URL_'][2];
        $renren = new Renren();
        $data = $renren->getAccessToken($code,$url,array('renren_id' => $renren_id));
        if(@$data->error){
            echo '没有获取到人人网的反馈，请退出重试';
            return;
        }
        $user = M('User');
        $user->where(array('username' => $username))->data(array('isConnect' => 1,'access_token' => $data->access_token,'refresh_token' => $data->refresh_token,'renren_id' => $renren_id))->save();
        echo '绑定成功';
        return;
    }
    
    /**
     * 特定公共主页操作
     */
    
    public function controll(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        $page['id'] = $this->_param(3);
        $page['name'] = $this->_param(4);
        $page['name'] = iconv("GB2312","UTF-8",$page['name']);
        $this->assign('page',$page);
        $this->display();
    }
    
    /**
     * 用于更新状态
     */
    public function setStatus(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        if(!$this->_post('status') ){
            $this->error('还什么都木有说啊亲');
            return;
        }
        $id = cookie('id');
        $page_id = $this->_post('page_id');
        $status = $this->_post('status');
        $user = new UserModel();
        $access_token = $user->getAccessToken($id);
        $renren = new Renren();
        $renren->setStatus($status,$access_token,$page_id);
        $this->success('状态发布成功');
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
     * 解除人人连接
     */
    public function disconnect(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        $renren = new Renren();
        $user = new UserModel();
        $id = cookie('id');
        $renren_id = $user->getRenrenID($id);
        $access_token = $user->getAccessToken($id);
        $email = $user->getEmail($id);
        $renren->disconnect($renren_id,$access_token,$email);
        $user->disConnect($id);
        $this->success('解除绑定成功',C('site_url'));
    }
    /**
     * 从人人解除连接，接收POST消息
     */
    public function disconnect_post(){
        $renren_id = $this->_post('xn_sig_user');
        $api_key = $this->_post('xn_sig_api_key');
        $app_id = $this->_post('xn_sig_app_id');
        $time = $this->_post('xn_sig_app_time');
        $added = $this->_post('xn_sig_app_added');
        $method = $this->_post('xn_sig_app_method');
        $sig = $this->_post('xn_sig_app_sig');
        $receive = 'user='.$renren_id.'key='.$api_key.'id='.$app_id.'time='.$time.'added='.$added.'method='.$method;
        $receive = md5($receive);
        if(!$sig == $receive){
            return;
        }
        if( !(($api_key == C('RENREN_APP_KEY') ) and ($app_id == C('RENREN_APP_ID') )) ){
            return;
        }
        $user = new UserModel();
        $user->disConnect_rev($renren_id);
        return;
        
    }
    
    public function test(){
        
    }
}
?>