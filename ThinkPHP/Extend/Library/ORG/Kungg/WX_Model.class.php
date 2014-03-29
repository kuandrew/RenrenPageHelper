<?php
class WX_Model extends Model{
    
    public $wx_id = '';
    public $statue = '';
    public $data = '';
    protected $trueTableName = 'wx';
    
    function WX_Model($wx_id,$data){
        parent::__construct();
        $this->wx_id = $wx_id;
        $this->data  = $data;
        $this->isIndex();
    }
    
    public function isIndex(){
        $condition['weixin_id'] = $this->wx_id;
        if(!$this->where($condition)->find()){
            $data['weixin_id'] = $this->wx_id;
            $this->data($data)->add();
            return ;
        }
    }
    

    
}
?>