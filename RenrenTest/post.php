<?php
$post = $_POST;
$timestamp = time();
$nonce = rand(0,912312);
setcookie('url',$post['url']);
setcookie('token',$post['token']);
setcookie('fromUser',$post['fromUser']);
setcookie('toUser',$post['toUser']);
$data[0] = $post['token'];
$data[1] = $timestamp;
$data[2] = $nonce;
sort($data,SORT_LOCALE_STRING);
$result = $data[0].$data[1].$data[2];
$signature = strtoupper(sha1($result));
$message = "<xml>
				    <ToUser><![CDATA[%s]]></ToUser>
				    <FromUser><![CDATA[%s]]></FromUser>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <SmallUrl><![CDATA[%s]]></SmallUrl>
                    <LargeUrl><![CDATA[%s]]></LargeUrl>
                    <Content><![CDATA[%s]]></Content>
				    </xml>";
if($post['largeurl'] || $post['smallurl']) $msgType = 'image';
$message = sprintf($message, $post['toUser'], $post['fromUser'], time(), $msgType,$post['smallurl'],$post['largeurl'],$post['content']);
$data = array(
	'timestamp' => $timestamp,
	'nonce' => $nonce,
	'signature' => $signature,
	'message' => $message
	);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post['url']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 我们在POST数据哦！
curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
curl_close($ch);
@$result  = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
echo '<p>'.$result->Content.'</p>';
echo '<p>'.$output.'</p>';

?>