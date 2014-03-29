<?php
class RrrestModel extends Model{

	/*
	获取当前路径
	$rid：用户的人人ID
	*/
	function getDir($rid){
		$data = $this->where(array('rid' => $rid))->find();
		if(!$data['dir']){
			if(!$data['rid']){
				$this->data(array('rid' => $rid,'dir' => 0))->add();
				$data = array(
					'dir' => 0,
					'name' => '_menu',
					'isNew' => 1
					);
				return $data;
			}
			$this->where(array('rid' => $rid))->data(array('dir' => 0))->save();
			$data = array(
				'dir' => 0,
				'name' => '_menu'
				);
			return $data;
		}
		$cache = str_replace('.', ',',$data['dir']);
		$map['did'] = array('in',$cache);
		$dir = M('Rrrest_dir');
		$cache = $dir->where($map)->select();
		foreach ($cache as $key => $value) {
			$result['name'] = $result['name'].'_'.$value['name'];
		}
		$result['dir'] = $data['dir'];
		return $result;
	}


	/*
	获取当前非影藏路径的帮助信息
	$dir:当前路径
	*/
	function getHelp($dir){
		$cache = explode('.',$dir);
		$dir = M('Rrrest_dir');
		$data = $dir->where(array('fid' => $cache[count($cache) - 1]))->select();
		if(!$data){
			$data = $dir->where(array('did' => $cache[count($cache) - 1]))->find();
			return $data['instruction'];
		}
		for($i = 0;$i < count($data);$i++){
			$result = $result.'回复'.($i+1).'：'.$data[$i]['content']."\n";
		}
		$result = $result.'回复0：回到上级菜单';
		return $result;
	}


	/*
	用户访问路径改变
	$rid:用户的人人ID
	$old:原来的路径
	$new:回复的路径字符
	$name:原来路径的方法名
	*/
	function changeDir($rid,$old,$new,$name){
		if($name == '_menu')$name = '';
		if($new == 0){
			$location = strrpos($old,'.');
			$old = substr($old,0,$location);
			$this->where(array('rid' => $rid))->save(array('dir' => $old));
			$cache = explode('.',$old);
			$location = strrpos($name,'_');
			$name = substr($name,0,$location);
			if($name == '_' || $name == '')$name = '_menu';
			$result['name'] = $name;
			$result['dir'] = $old;
			return $result;
		}
		$cache = explode('.',$old);
		$dir = M('Rrrest_dir');
		$data = $dir->where(array('fid' => $cache[count($cache) - 1]))->select();
		if(!$data){
			$result['name'] = $name;
			$result['dir'] = $old;
			return $result;
		}
		$dir_n = $old.'.'.$data[$new - 1]['did'];
		$this->where(array('rid' => $rid))->save(array('dir' => $dir_n));
		$result['name'] = $name.'_'.$data[$new - 1]['name'];
		$result['dir'] = $dir_n;
		return $result;
	}
}