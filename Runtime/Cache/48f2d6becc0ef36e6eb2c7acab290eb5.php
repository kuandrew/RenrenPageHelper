<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>公共主页 - 手机助手</title>
    <link rel="shortcut icon" href="<?php echo C('SITE_URL');?>pub/favicon.ico" type="image/x-icon" />
	<!-- Bootstrap css file v2.2.1 -->
	<link rel="stylesheet" type="text/css" href="<?php echo C('SITE_URL');?>pub/bootstrap/css/bootstrap.css">

	<!--[if lte IE 6]>
	<!-- bsie css 补丁文件 -->
	<link rel="stylesheet" type="text/css" href="<?php echo C('SITE_URL');?>pub/bootstrap/css/bootstrap-ie6.css">
    <![endif]-->
    <!--[if lte IE 7 or IE 8]>
	<!-- bsie 额外的 css 补丁文件 -->
	<link rel="stylesheet" type="text/css" href="<?php echo C('SITE_URL');?>pub/bootstrap/css/ie.css">
	<![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo C('SITE_URL');?>pub/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<style>
	a:hover{
	TEXT-DECORATION:none;
	}
	</style>
</head>
<body data-target=".bs-docs-sidebar" data-spy="scroll">
<div class="container pull-center"><div class="title" style="margin: 50px;"><h1>完成账号捆绑</h1></div>
<div class="form">
    <?php echo W('Form',array('url'=>C("SITE_URL").'index.php/User/reg_wx'));?>
    <h2>你的人人账号是：<?php echo ($renren_name); ?></h2>
    <h2>你的人人ID是：<?php echo ($renren_id); ?></h2>
    <h2>用户名</h2>
    <input name="renren_id" style="display: none;" value="<?php echo ($renren_id); ?>" />
    <input name="username" type="text" style="height:30px;" value="<?php echo ($username); ?>" />
    <h2>邮箱（找回密码用得到哦）</h2>
    <input name="email" type="text" style="height:30px;"/>
    <h2>密码</h2>
    <input name="password" type="password" style="height: 30px;"/></br>
    <input name="wx_id" style="display: none;" value="<?php echo ($wx_id); ?>" />
    <input type="submit" class="btn btn-primary btn-large" style="margin: 10px 0px 0px 0px;" value="完成注册" />
    </form>
</div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<!-- Optional, bootstrap javascript library -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/bootstrap/js/bootstrap.js"></script>

<!--[if lte IE 6]>
<!-- bsie js 补丁只在IE6中才执行 -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/js/bootstrap-ie.js"></script>
<![endif]-->
</body>
</html>