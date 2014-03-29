<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>公共主页 - 手机助手</title>
    <meta name="description" content="公共主页手机助手 让用户在手机上管理自己的人人公共主页">
    <meta name="keywords" content="公共主页,手机助手,人人网,人人">
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
<div class="container" style="position: relative;">
<div class="form">
    <?php echo W('Form',array('url'=>C("SITE_URL").'index.php/Index/log'));?>
    <h2>用户名</h2>
    <input name="username" type="text" style="height:30px;width: 234;" />
    <h2>密码</h2>
    <input name="password" type="password" style="height: 30px;width: 234;"/></br>
    <input type="submit" class="btn btn-primary btn-large" style="margin: 10px 0px 0px 0px;padding: 12px 98px 12px 98px;" value="登陆" />
    </form>
    <a  onclick="reg();" href="<?php echo U('Index/reg');?>"><input type="submit" class="btn btn-primary btn-large" style="margin: 0px 0px 10px 0px;padding: 12px 98px 12px 98px;" value="注册" /></a>
    <br />
    <a href="https://graph.renren.com/oauth/authorize?display=page&client_id=<?php echo C('RENREN_APP_KEY');?>&redirect_uri=<?php echo C('site_url');?>index.php/<?php echo C('RENREN_CALLBACK');?>&response_type=code&scope=<?php echo C('RENREN_SCOPE');?>"><img src="http://wiki.dev.renren.com/mediawiki/images/b/b9/234_48dark.png" /></a>
</div>
<div style="margin: 16px;">
<img src="<?php echo C('SITE_URL');?>pub/qccode.jpg" style="width: 200px; height: 200px;">
<p>扫一扫</p><p>添加rrsjzs为订阅号</p><p>在微信上管理公共主页</p>                
</div>
</div>

</div>
<div class="link pull-center" >
<a href="http://www.renren.com" target="_blank" >人人网</a>
&nbsp;
<a href="http://www.36kr.com" target="_blank" >36氪</a>
</div>
<div class="pull-center" style="padding:10px; ">
<a onclick="addbug();">建议&nbsp;BUG</a>&nbsp;
<script language="javascript" type="text/javascript" src="http://js.users.51.la/16530268.js"></script>
</div>
<div class="bug pull-center" ></div>
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
<script type="text/javascript">
function reg(){
    window.location.href="<?php echo U('Index/reg');?>";
}
</script>
</body>
</html>