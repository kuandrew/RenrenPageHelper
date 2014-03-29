<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    
    public function IndexAction(){
        parent::__construct();
        import('ORG.Crypt.Crypt');
        import('ORG.Kungg.Renren');
    }
    
    

    
    
    public function index(){
        
        if($this->isLog()){
            $this->redirect('Index/start/'); //跳转到用户主界面
            return;
        }
        $csrf = new CsrfAction();
        if($csrf->isCsrf()){
            return;
        }
        $data['site_url'] = C('site_url');
        $this->assign($data);
        $renren = new Renren();
        $renren->getAccessToken();
        $this->display();
	}
    
    
    
    
    
    
    /**
     * 用户主界面
     */
     
     
    public function start(){
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
        if(!$user->isConnect($id)){
            $this->error('亲，你还没有绑定人人网哦，请先绑定',U('Index/detail'));
            return;
        }
        $access_token = $user->getAccessToken($id);
        $data = $renren->getPageList($access_token);
        $data[count($data)]->name = '自己的人人主页';
        $data[count($data)]->page_id = 0;
        if(!$data[0]->page_id){
            //$this->error('亲，你还木在人人上开通过公共主页。',U('Index/detail'));
            //return;
            echo $data->page_id;
            $data['message'] = '亲，你还木在人人上开通过公共主页。';
        }
        $this->assign('data',$data);
        $this->display();
    }
    
    
    /**
     * 检测用户是否登录
     */
     
     
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
    
    public function log(){
        $username = $this->_post('username');
        $password = $this->_post('password');
        $password = sha1($password);
        if (!$username or !$password){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，你还没有写用户名和密码呢',C('SITE_URL'));
            return;
        }
        $user = new UserModel();
        $id = $user->log($username,$password);
        if (!$id){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，账号或者密码错了',C('SITE_URL'));
            return;
        }
        $crypt = new Crypt();
        cookie('username',$crypt->encrypt($username,C('Secret_Key')));
        cookie('password',$password);
        cookie('id',$id);
        $this->success('登录成功',C('site_url'));
    }
    
    public function detail(){
        if(!$this->isLog()){
            cookie('username',NULL);
            cookie('password',NULL);
            cookie('id',NULL);
            $this->error('亲，还木有登陆呢',C('site_url'));
            return;
        }
        $renren = new Renren();
        $id = cookie('id');
        $user = new UserModel();
        $isConnect = $user->isConnect($id);
        $renren_id = $user->getRenrenID($id);
        $access_token = $user->getAccessToken($id);
        $email = $user->getEmail($id);
        $this->assign('isConnect',$isConnect);
        $this->display();
    }
    
    public function logout(){
        cookie('username',NULL);
        cookie('password',NULL);
        cookie('id',NULL);
        $this->success('成功退出',C('SITE_URL'));
    }
    
    /**
     * 用户注册
     */
    public function reg(){
        $this->display();
    }

    /**
    提交bug
    */
    public function bug(){
        $content['bug'] = $this->_post('bug');
        $bug = D('bug');
        $bug->data($content)->add();
        return;

    }

}