<?php
/**
 * 此类库用于微信公众平台
 * @author Ku_Andrew<kungg@kungg.com>
 */
class Weixin{
    
    
    public function Weixin($str){
        define('WX_TOKEN',C('Weixin_TOKEN'));
        $this->receive($str);
        $this->analyse();  
    }
    
    public function WeixinTest(){
        define('WX_TOKEN',C('Weixin_TOKEN'));
        $this->receive($str);
        $this->analyse();
        $model = new WX_Model($this->fromUser,$this->msg);  
    }
    
    
    public $type       = '';          //用户发来消息的类别
    public $msg        = '';          //用户发来的消息
    public $fromUser   = '';          //用户微信账号
    public $toUser     = '';          //本微信账号
    public $picURL     = '';          //上传图片的地址
    public $analyse    = '';          //解析用户输入内容  help:获取帮助信息 bind:进行绑定 show:显示绑定的公共主页
    public $help       = "1.回复 bd : 注册和绑定。\n
                          2.回复 show : 查看你所管理的公共主页id\n
                          3.回复 公共主页id#内容 : 发布内容 例:123#手机助手 会在id为123的公共主页发布一条状态“手机助手”\n
                          4.回复 #内容 : 直接在你的人人主页发布状态\n
                          4.回复 jcbd : 解除绑定\n
                          5.回复 h : 查看帮助文本";
    
    /*public function __construct($str = ''){
        $this->receive($str);
        $this->analyse();      
    }*/
    
    
    
    
    /**
     * 用于微信接入
     * 传入$timestamp和$nonce 与WX_TOKEN进行sha1运算
     * 如果正确，则返回1，否则0
     */
     function wx_in($timestamp,$nonce,$signature){
        $word = 'token='.WX_TOKEN.'timestamp='.$timestamp.'nonce='.$nonce;
        $secret = sha1($word);
        if($signature == $secret){
            return 1;
        }
        return;
     }
     
     /**
      * 解析微信POST过来的信息，判断信息的类别
      */
     function receive($str){
        if(empty($str)){
            return 0;
        }
        $obj = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->fromUser = iconv("UTF-8", "GB2312//IGNORE",$obj->FromUserName);
        $this->toUser   = iconv("UTF-8", "GB2312//IGNORE",$obj->ToUserName);
        $this->type     = iconv("UTF-8", "GB2312//IGNORE",$obj->MsgType);
        $this->msg      = $obj->Content;//iconv("UTF-8", "GB2312//IGNORE",$obj->Content);
        
        return $this->type;
     }
     
     /**
      * 发送text类型消息
      */
    function send_text($msg){
        $msg      = $msg;
        $fromUser = $this->fromUser;
        $toUser   = $this->toUser;
        $keyword  = trim($obj->Content);
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
        //$renren = new Renren();
        //$renren->setStatus($keyword.'  这是一条同步自微信的状态','235544|6.52b6ace2d9c8d24023d89c06a82ec111.2592000.1381683600-266379585','601663496');
                    
    }
    
    /**
     * 解析用户发送的消息
     */
    function analyse(){
        $msg = trim($this->msg);
        $msg = strtolower($msg);
        if($this->type == 'image'){
            $this->analyse = 'image';
            return;
        }
        if($msg == 'help' or $msg == 'HELP' or $msg == '帮助' or $msg == 'h' or !$msg){
            $this->analyse = 'help';
            return;
        }
        if($msg == 'bd'){
            $this->analyse = 'bind';
            return;
        }
        if($msg == 'show'){
            $this->analyse = 'show';
            return;
        }
        if($msg == 'jcbd'){
            $this->analyse = 'jcbd';
            return;
        }
        if(count(explode('#',$msg)) > 1){
            $this->analyse = 'set_statue';
            return;
        }
        if($msg == 'reg'){
            $this->analyse = 'reg';
        }
    }
}
?>