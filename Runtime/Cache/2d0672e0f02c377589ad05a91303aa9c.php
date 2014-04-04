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
<div class="container pull-center">
<div class="title" style="margin: 50px 0px 0px 0px;">
<h1>公共主页手机助手</h1>
<h2>hello,<strong class="text-error"><?php echo $username; ?></strong></h2>
</div>
<hr />
<div class="page_list">
<?php  for($i = 0; $i < count($data); $i++){ echo '<a href="'.C('SITE_URL').'index.php/Renren/controll/'.$data[$i]->page_id.'/'.$data[$i]->name.'" ><h3>'.$data[$i]->name.'</h3></a>'; echo ''; } echo '<h3>'.$data['message'].'</h3>'; ?>
</div>
<hr />
<div class="footer">
<div class="user pull-center">
<a href="<?php echo C('SITE_URL');?>index.php/Index/detail/">设置</a>
&nbsp;
<a href="<?php echo C('SITE_URL');?>index.php/Index/logout/">退出</a>
</div>
<div class="link pull-center">
<a href="http://www.renren.com" target="_blank" >人人网</a>
&nbsp;
<a href="http://www.36kr.com" target="_blank" >36氪</a>
</br>
<a href="http://page.renren.com/699235544/index" target="_blank">Contact&nbsp;US</a>
</div>
</div>
<div style="margin:0px 0px 10px 0px; ">
<a onclick="addbug();">建议&nbsp;BUG</a>&nbsp;
<script language="javascript" type="text/javascript" src="http://js.users.51.la/16530268.js"></script>
</div>
</div>
<div class="bug pull-center"></div>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<!-- Optional, bootstrap javascript library -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
function addbug(){
    $('.bug').empty();
    var data = '<textarea class="form-control" rows="3" name="bug"></textarea></br><input type="submit" class="btn btn-primary btn-small" style="margin: 10px 0px 0px 0px;padding: 10px 80px 10px 80px;;" value="告诉我们" onclick="bugSubmit();" />';
    $(".bug").append(data);
    $("html,body").animate({scrollTop:$(document).height()},'slow');
}
function bugSubmit(){
    $.post('<?php echo U("Index/bug");?>',
            {
                bug:$('textarea').attr('value'),
            },
            function(result){
                $('.bug').empty();
            }
    );

}
</script>
<!--[if lte IE 6]>
<!-- bsie js 补丁只在IE6中才执行 -->
<script type="text/javascript" src="<?php echo C('SITE_URL');?>pub/js/bootstrap-ie.js"></script>
<![endif]-->
</body>
</html>