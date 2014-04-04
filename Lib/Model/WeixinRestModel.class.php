<?php
class WeixinRestModel extends Model{

	protected $trueTableName = 'wxrest';
	
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

	/*
	*是否绑定 
	*0 没有绑定网站和人人
	*-1 绑定了网站但是没绑定人人
	*-2 没绑定网站，但绑定了人人
	*1 绑定了网站和人人
	*/
	function isBind($wxid){
		$cache = $this->where(array('weixin_id' => $wxid))->find();
		$user = M('user')->where(array('id' => $cache['uid']))->find();
		if(!$cache['uid']){
			if($user['renren_id'])return -2;
			return 0;
		}
		if($user['renren_id']){
			return 1;
		}
		return -1;
	}


	/*
	*切换目录
	*$dir：当前目录位置
	*step: 正数前进，负数后退
	*$choice: $steo为正时必填，下一层进入的目录 格式：'1.1.2.3'
	*/
	function changeDir($wxid,$dir = '',$step,$choice=null){
		if($dir == ''){
			$dir = $this->where(array('weixin_id' => $wxid))->find();
			$dir = $dir['dir'];
		}
		if($step > 0){
			$dir = $dir.'.'.$choice;			
		}else{
			$step = abs($step);
			for($i = 0;$i < $step;$i++){
				if(strlen($dir) == 1){
					$dir = '0';
					continue;
				}
				$dir = preg_replace('/\.([^\.]*?)$/','',$dir);
			}
		}
		$this->where(array('weixin_id' => $wxid))->save(array('dir' => $dir));
		return $dir;
	}

}