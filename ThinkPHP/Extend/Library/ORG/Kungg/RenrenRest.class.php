<?php
/**
 * 此类库用于人人API
 * @author Ku_Andrew<kungg@kungg.com>
 */
 
class RenrenRest{
    public function RenrenRest($str){
        define('RENREN_PUBLIC_TOKEN',C('RENREN_PUBLIC_TOKEN'));
        $this->receive($str);
        //$this->analyse();  
    }
    
    public $type       = '';          //用户发来消息的类别
    public $content    = '';          //用户发来的消息
    public $fromUser   = '';          //用户微信账号
    public $toUser     = '630000282';          //本公众号账号
    public $smallURL   = '';          //上传图片的地址
    public $largeURL   = '';
    public $analyse    = '';          //解析用户输入内容  help:获取帮助信息 bind:进行绑定 show:显示绑定的公共主页
    public $help       = "1.回复 bd : 注册和绑定。\n
                          2.回复 show : 查看你所管理的公共主页id\n
                          3.回复 公共主页id#内容 : 发布内容 例:123#手机助手 会在id为123的公共主页发布一条状态“手机助手”\n
                          4.回复 #内容 : 直接在你的人人主页发布状态\n
                          4.回复 jcbd : 解除绑定\n
                          5.回复 h : 查看帮助文本";
   
    
    function renren_in($timestamp,$nonce,$signature){
        $word = 'token='.WX_TOKEN.'timestamp='.$timestamp.'nonce='.$nonce;
        $secret = sha1($word);
        if($signature == $secret){
            return 1;
        }
        return;
     }
     
     
     function receive($str){
        if(empty($str)){
            return 0;
        }
        $obj = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->fromUser = iconv("UTF-8", "GB2312//IGNORE",$obj->FromUser);
        $this->toUser   = iconv("UTF-8", "GB2312//IGNORE",$obj->ToUser);
        $this->type     = iconv("UTF-8", "GB2312//IGNORE",$obj->MsgType);
        $this->content  = $obj->Content;
        $this->smallURL = $obj->SmallUrl;
        $this->largeURL = $obj->LargeUrl;
        return $this->type;
     }
     
     function send_text($msg,$fromUser = 1){
        $msg      = $msg;
        $toUser   = $this->toUser;
        $time     = time();
        $msgType  = 'text';
        $textTpl = "<xml>
        <ToUser><![CDATA[%s]]></ToUser>
        <FromUser><![CDATA[%s]]></FromUser>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $resultStr = sprintf($textTpl, $fromUser, $toUser, $time, $msgType, $msg);
       	echo $resultStr;
        return;       
    }

    function send_news($title,$content,$pic = 'http://fmnp.rrimg.com/fmn058/20131122/2055/original_EueA_161200000330125f.png',$url,$fromUser = 1){
        $time     = time();
        if($pic == '')$pic = 'http://fmnp.rrimg.com/fmn058/20131122/2055/original_EueA_161200000330125f.png';
        $toUser   = $this->toUser;
        $textTpl = '
        <xml>
        <ToUser><![CDATA[%s]]></ToUser>
        <FromUser><![CDATA[%s]]></FromUser>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>1</ArticleCount>
        <Articles>
        <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
        </Articles>
        </xml>
        ';
        $resultStr = sprintf($textTpl, $fromUser, $toUser, $time, $title, $content, $pic, $url);
        echo $resultStr;
        return;
    }                       
}
?>