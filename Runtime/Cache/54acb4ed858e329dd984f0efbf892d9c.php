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
<div class="container pull-center"><div class="title" style="margin: 50px;"><h1>公共主页手机助手</h1></div>
<div class="form">
    <h2>有注册过手机助手吗？</h2>
    <button class="btn btn-large btn-primary" onclick="yes()" style="margin: 10px;">&nbsp;&nbsp;有&nbsp;&nbsp;</button>
    </br>
    <button class="btn btn-large btn-primary" onclick="no()" >没有</button>
    <h3>点击“没有”进行注册，注册完成后，再进行绑定。</h3>
</div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<!-- Optional, bootstrap javascript library -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/bootstrap/js/bootstrap.js"></script>

<!--[if lte IE 6]>
<!-- bsie js 补丁只在IE6中才执行 -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/js/bootstrap-ie.js"></script>
<![endif]-->
<script type="text/javascript">
function yes(){
    window.location.href='<?php echo C("SITE_URL");?>index.php/WeixinRest/bind_log?wx_id=<?php echo ($wx_id); ?>';
}
function no(){
    window.location.href='https://graph.renren.com/oauth/authorize?display=page&client_id=<?php echo C('RENREN_APP_KEY');?>&redirect_uri=<?php echo C('site_url');?>index.php/<?php echo C('RENREN_CALLBACK');?>&response_type=code&scope=read_user_album+read_user_feed+status_update+admin_page';
}
</script>
</body>
</html>