<?php
$config = array(
	'WX' => array(
		'TOKEN' => '',
		/*
		目录格式：
		x => array()
		回复xx即跳转
		name:菜单中显示的名字
		content:默认回复的内容（仅限最后一层）（如果为空，则说明有下一级菜单）
		function:调用控制器方法
		*/
		'DIR' => array(


			1 => array(
				'name' => '发布',
				'content' => '',
				'function' => '_publish',
				1 => array(
					'name' => '发状态',
					'content' => '回复“%s#内容”，向“%s”发状态',
					'function' => '_status',
					),
				2 => array(
					'name' => '发图片',
					'content' => '请先发一张图片给我，然后回复英文字母进行发布。',
					'function' => '_photo',
					),
				),


			2 => array(
				'name' => '绑定',
				'content' => '',
				'function' => '_bind',
				1 => array(
					'name' => '绑定',
					'content' => '',
					'function' => '_bind',
					1 => array(
						'name' => '未注册用户绑定',
						'content' => '<a href="">点击我注册</a>',
						'function' => '_reg',
						),
					2 => array(
						'name' => '已注册用户绑定',
						'content' => "回复 “账号#密码” 后自动绑定\n不是人人网账号哦亲，是助手账号。",
						'function' => '_bind',
						),
					),
				2 => array(
					'name' => '解除绑定',
					'content' => '确认解绑请输入yes',
					'function' => '_unbind',
					),
				),
			3 => array(
				'name' => '获取帮助',
				'content' => '暂时没有帮助信息',
				'function' => '_help',
				),
			),
		),
	);
return $config;
?>