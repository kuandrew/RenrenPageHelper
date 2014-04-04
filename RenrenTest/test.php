<html>
<head>
<meta charset="utf8"></meta>
<title>test</title>
<script src="jquery-1.7.2.min.js"></script>
</head>
<body>
	
		rest地址<input id="url" name="url" value="<?php echo $_COOKIE['url'] ?>"/></br>
		TOKEN<input id="token" name="token" value="<?php echo $_COOKIE['token'] ?>" /></br>
		fromUser<input id="fromUser" name="fromUser" value="<?php echo $_COOKIE['fromUser'] ?>" /></br>
		toUser<input id="toUser" name="toUser" value="<?php echo $_COOKIE['toUser'] ?>" /></br>
		smallurl<input id="smallurl" name="smallurl" value="<?php echo $_COOKIE['smallurl'] ?>" /></br>
		largeurl<input id="largeurl" name="largeurl" value="<?php echo $_COOKIE['largeurl'] ?>" /></br>
		内容<input id="content" name="content" /></br>
		你说：<p id="say"></p>
		回复：<div id="response"></div>
		<input type="submit" onclick="post()" value="提交" />
		
<script type="text/javascript">
function post(){
	$('#response').empty();
	$('#say').text($('#content').val());
	$.post('post.php',
  {
  	url:$('#url').val(),
  	token:$('#token').val(),
  	fromUser:$('#fromUser').val(),
  	toUser:$('#toUser').val(),
  	smallurl:$('#smallurl').val(),
  	largeurl:$('#largeurl').val(),
  	content:$('#content').val(),
  },
  function(data,status){
  	$('#response').append(data);
  });
}
</script>	
</body>
</html>