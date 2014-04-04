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
					'content' => '请先发一张图片给我，然后进行发布。',
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
						'name' => '已经在小助手网站注册过',
						'content' => "请输入小助手网站的用户名和密码\n格式：用户名#密码\n直接回复即可",
						'function' => '_bind',
						),
					2 => array(
						'name' => '还没有注册过',
						'content' => '<a href="">点击我绑定</a>',
						'function' => '_reg',
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
				'content' => "首先，你需要到绑定账号哦。
							\n然后，你就可以随意使用了。
							\n如有疑问，请加我的人人 冉坤
							\nkungg@kungg.com
							\nhave fun & enjoy yourself
							\n交流群：109948587 
							\n回复0返回",
				'function' => '_help',
				),
			),
		),
);
return $config;
?>