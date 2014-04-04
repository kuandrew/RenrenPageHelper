<?php
class WeixinRest {

    public $type       = '';          //用户发来消息的类别
    public $content    = '';          //用户发来的消息
    public $fromUser   = '';          //用户微信账号
    public $toUser     = '';          //本微信账号
    public $picURL     = '';          //上传图片的地址
    public $analyse    = '';          //解析用户输入内容  help:获取帮助信息 bind:进行绑定 show:显示绑定的公共主页
    public $help       = '';



    public function WeixinRest($str){
        if(empty($str)){
            return 0;
        }
        $obj = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->fromUser = iconv("UTF-8", "GB2312//IGNORE",$obj->FromUserName);
        $this->toUser   = iconv("UTF-8", "GB2312//IGNORE",$obj->ToUserName);
        $this->type     = iconv("UTF-8", "GB2312//IGNORE",$obj->MsgType);
        $this->picURL   = iconv("UTF-8", "GB2312//IGNORE",$obj->PicUrl);
        $this->content  = $obj->Content;//iconv("UTF-8", "GB2312//IGNORE",$obj->Content);
        return $this;
    }




    public function sendText($msg,$fromUser){
        $msg      = $msg;
        $fromUser = $fromUser;
        $toUser   = $this->toUser;
        $time     = time();
        $msgType  = 'text';
        $textTpl = "<xml>
				    <ToUserName><![CDATA[%s]]></ToUserName>
				    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
				    </xml>";
        $resultStr = sprintf($textTpl, $fromUser, $toUser, $time, $msgType, $msg);
        //$resultStr = iconv("UTF-8", "GB2312//IGNORE",$resultStr);
       echo $resultStr;
    }
	
}