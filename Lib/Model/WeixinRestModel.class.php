<?php
class WeixinRestModel{
	
	function analyse($weixin_id){
		$data = $this->where(array('weixin_id' => $weixin_id))->find();
		if(!$data){
			$data = array(
				'weixin_id' => $weixin_id,
				'dir' => 0,
				'createtime' => time(),
				'lasttime' => time(),
				);
			$this->add($data);
		}
		return $data;
	}

}