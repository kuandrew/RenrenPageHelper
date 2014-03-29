<?php
class UserModel extends Model{

    protected $trueTableName = 'user';
    
    
    public function UserModel(){
        parent::__construct();
    }
    
    public function isLog($username = 0,$password = 0,$id = 0){
        $condition['username'] = $username;
        $condition['password'] = $password;
        $condition['id']       = $id;
        $this->where($condition)->find();
        if($this->data){
            return 1;
        }
    }
    /**
     * 更具用户人人返回的renren_id 判断用户是否已经注册本网站了
     * 如果是，则更新Access_Token和Refresh_Token
     * 如果否，则创建新账号
     */
    public function refreshAccessToken($renren_id,$access_token = '',$refresh_token = ''){
        $condition['renren_id'] = $renren_id;
        $this->where($condition)->find();
        if ($this->data['id']){   //如果用户ID已经存在，继续检测是否有用户名
            if ($this->data['username']){ //如果用户名存在，则说明已经完成过注册，那么更新ACCESS_TOKEN和REFRESH_TOKEN
                $data['access_token'] = $access_token;
                $data['refresh_token'] = $refresh_token;
                $data['isConnect'] = 1;
                $this->where($condition)->data($data)->save();
                return 1;
            }
            return 0; //用户名不存在，说明用户已经授权过ACCESS信息，那么什么也不做返回0
            
            
        } else {               //如果还没有注册，添加人人的ID和ACCESS
            $data['renren_id'] = $renren_id;
            $data['access_token'] = $access_token;
            $data['refresh_token'] = $refresh_token;
            $data['isConnect'] = 1;
            $this->add($data);
            return 0;
        }
    }
    
    /**
     * 通过twice接口（设置页面）获取的Access_Token
     */
    
    public function reg_twice($id,$access_token,$refresh_token,$renren_id){
        $condition['id'] = $id;
        $data['access_token'] = $access_token;
        $data['refresh_token'] = $refresh_token;
        $data['renren_id'] = $renren_id;
        $data['isConnect'] = 1;
        $this->where($condition)->data($data)->save();
        return;
    } 
    
    /**
     * 直接通过网站注册，没走人人
     */
    
    public function regnocaccess($username,$password,$email){
        $data['username'] = $username;
        $this->where($data)->find();
        if($this->data['username']){
            return 2;
            //用户名已经存在，返回2
        }
        $data['isConnect'] = 0;
        $data['password'] = sha1($password);
        $data['email'] = $email;
        $data['createtime'] = time();
        $this->data($data)->add();
        $this->where($data)->find();
        return $this->data;
        
    }
    
    /**
     * 保存用户注册信息，根据renren_id来更新username和password
     */
    
    public function reg($username,$password,$renren_id,$email,$wx_id = 0){
        $condition['renren_id'] = $renren_id;
        $this->where($condition)->find();
        if($this->data['username'] or $this->data['password']){
            return;
        }
        $data['isConnect'] = 1;
        $data['username'] = $username;
        $data['password'] = sha1($password);
        $data['email'] = $email;
        $data['weixin_id'] = $wx_id;
        $this->where($condition)->data($data)->save();
        $this->where($condition)->find();
        return $this->data['id'];
    }
    
    
    /**
     * 用户使用人人连接登录后获取账号 密码 ID
     */
    public function getPassword($renren_id){
        $condition['renren_id'] = $renren_id;
        $this->where($condition)->find();
        $data['username'] = $this->data['username'];
        $data['password'] = $this->data['password'];
        $data['id'] = $this->data['id'];
        return $data;
    }
    /**
     * 从数据库获取Access_Token
     */
    public function getAccessToken($id){
        $condition['id'] = $id;
        $this->where($condition)->find();
        return $this->data['access_token'];
    }
    /**
     * 用于登录 log()
     */
    public function log($username,$password){
        $condition['username'] = $username;
        $condition['password'] = $password;
        $this->where($condition)->find();
        if(!$this->data['username'] or !$this->data['password']){
            return;
        }
        return $this->data['id'];
    }
    /**
     * 修改密码
     */
    public function passwordset($id,$password){
        $condition['id'] = $id;
        $data['password'] = $password;
        $this->where($condition)->data($data)->save();
    }
    /**
     * 是否与人人网连接，从数据库读取，请勿完全相信
     */
    public function isConnect($id){
        $condition['id'] = $id;
        $this->where($condition)->find();
        return $this->data['isConnect'];
    }
    /**
     * 获取人人ID
     */
    public function getRenrenID($id){
        $condition['id'] = $id;
        $this->where($condition)->find();
        return $this->data['renren_id'];
    }
    /**
     * 获取用户邮箱
     */
    public function getEmail($id){
        $conditon['id'] = $id;
        $this->where($conditon)->find();
        return $this->data['email'];
    }
    /**
     * 解除绑定后修改状态isConnect = 0
     */
    public function disConnect($id){
        $condition['id'] = $id;
        $data['isConnect'] = 0;
        $data['access_token'] = '';
        $data['refresh_token'] = '';
        $data['renren_id'] = '';
        $this->where($condition)->data($data)->save();
        
    }
    /**
     * 从人人内部解除绑定
     */
    public function disConnect_rev($renren_id){
        $condition['renren_id'] = $renren_id;
        $data['isConnect'] = 0;
        $data['access_token'] = '';
        $data['refresh_token'] = '';
        $data['renren_id'] = '';
        $this->where($condition)->data($data)->save();        
    }
}
?>