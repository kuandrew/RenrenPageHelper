<?php
class WeixinModel extends Model{
    
    protected $trueTableName = 'user';
    
    public function WeixinModel(){
        parent::__construct();
    }
    
    public function isConnect($wx_id){
        $condition['weixin_id'] = $wx_id;
        $this->where($condition)->find();
        return $this->data['weixin_id'];
    }
    
    public function log($username,$password){
        $condition['username'] = $username;
        $condition['password'] = $password;
        $this->where($condition)->find();
        if($this->data){
            return 1;
        }
    }
    
    public function bind($username,$wx_id){
        $conditon['username'] = $username;
        $data['weixin_id'] = $wx_id;
        $this->where($conditon)->data($data)->save();
    }
    
    public function getData($wx_id){
        $condition['weixin_id'] = $wx_id;
        $this->where($condition)->find();
        return $this->data;
    }
    
    public function unbind($wx_id){
        $condition['weixin_id'] = $wx_id;
        $data['weixin_id'] = '';
        $this->where($condition)->setField($data);
    }
    
}
?>