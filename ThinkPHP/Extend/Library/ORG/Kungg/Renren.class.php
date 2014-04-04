<?php
/**
 * 此类库用于人人API
 * @author Ku_Andrew<kungg@kungg.com>
 */
class Renren {  
    /**
     * 通过返回CODE换取ACCESS_TOKEN
     */
     
    //protected $APP_KEY      = C('RENREN_APP_KEY'); //应用的APP_KEY
    //protected $APP_SECRET   = C('RENREN_APP_SECRET'); //应用的SECRET_KEY
    //protected $CALLBACK_URL = C('RENREN_CALLBACK');            //应用回调地址
    
    public function Renren(){
        define('APP_KEY',C('RENREN_APP_KEY'));
        define('APP_SECRET',C('RENREN_APP_SECRET'));
        define('CALLBACK',C('SITE_URL').'index.php/'.C('RENREN_CALLBACK'));
        define('CALLBACK_TWICE',C('SITE_URL').'index.php/'.C('RENREN_CALLBACK_TWICE'));
    }
    
    /**
     * 获取用户Access_Token
     */
    function getAccessToken($code = '',$redirect_uri = '',$arr = ''){
        if (!$code){
            return;
        }
        if($arr){
            $url = C('ROOT_URL').$redirect_uri.'?';
            foreach ($arr as $key => $value) {
                $url = $url.$key.'='.$value;
            }
        }elseif($redirect_uri){
            $url = C('ROOT_URL').$redirect_uri;
        }else{
            $url = CALLBACK;
        }
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.APP_KEY.'&redirect_uri='.$url.'&client_secret='.APP_SECRET.'&code='.$code); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://graph.renren.com/oauth/token"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
        
    }
    
    /**
     * 通过微信注册时获取Access_Token
     */
    function getAccessToken_wx($code = '',$wx_id){
        if (!$code){
            return;
        }
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.APP_KEY.'&redirect_uri='.CALLBACK.'/reg_wx/'.$wx_id.'&client_secret='.APP_SECRET.'&code='.$code); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://graph.renren.com/oauth/token"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
        
    }
    
    /**
     * 后期绑定过程获取用户Access_token
     */
     
     function getAccesstoken_twice($code = ''){
        if (!$code){
            return;
        }
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.APP_KEY.'&redirect_uri='.CALLBACK_TWICE.'&client_secret='.APP_SECRET.'&code='.$code); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://graph.renren.com/oauth/token"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
     }
     
    /**
     * 刷新Access_token
     */
    function refresh_token($refresh_token){
        if(!$refresh_token){
            return;
        }
        $post = 'grant_type=refresh_token&refresh_token='.$refresh_token.'&client_id='.APP_KEY.'&client_secret='.APP_SECRET;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://graph.renren.com/oauth/token"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
    
    /**
     * 转化email_hash，通过email
     */
    function toEmailHash($email){
        $head = sprintf('%u',crc32($email));
        $tail = md5($email);
        $email_hash = $head.'_'.$tail;
        return $email_hash;
    }
    
    /**
     * 绑定email_hash地址，本方法用到的加密因子是email值
     */
    function regEmailHash($renren_id,$access_token = '',$email){
        $email_hash = $this->toEmailHash($email);
        $post = 'v=1.0&format=json&method=connect.registerUsers&access_token='.$access_token.'&api_key='.APP_KEY.'&accounts=[{"email_hash":"'.$email_hash.'"}]';
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
    
    /**
     * 获取用户管理的PAGE列表
     */
    function getPageList($access_token){
        $post = 'v=1.0&format=json&method=pages.getManagedList&count=1000&access_token='.$access_token.'&client_id='.APP_KEY;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
    
    /**
     * 获取用户信息，不一定是已经绑定的用户
     */
    function getUserInfo($renren_id,$access_token = ''){
        $post = 'v=1.0&format=json&method=users.getInfo&access_token='.$access_token.'&client_id='.APP_KEY.'&fields=email_hash';
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        dump($result);
        return $result;
    }
    
    function setStatus($status,$access_token,$page_id){
        $post = 'v=1.0&format=json&method=status.set&status='.$status.'&access_token='.$access_token.'&page_id='.$page_id;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }

    function setPageStatus($status,$access_token,$page_id){
        $post = 'v=1.0&format=json&method=pages.setStatus&status='.$status.'&access_token='.$access_token.'&page_id='.$page_id;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
    
    
    /**
     * 判断是否已经授权
     */
    function isConnect($access_token){
        //$post = 'access_token='.$access_token;
        $ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_POST, 0); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,'https://api.renren.com/v2/feed/list?pageSize=1&access_token=1'.$access_token); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        if(!$result->response){
            return 0;
        }
        return 1;
    }
    
    /**
     * 解除绑定
     */
    function disconnect($renren_id,$access_token,$email){
        $email_hash = $this->toEmailHash($email);
        $post = 'v=1.0&format=json&method=connect.unregisterUsers&access_token='.$access_token.'&api_key='.APP_KEY.'&email_hashes=["'.$email_hash.'"]';
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_TIMEOUT,0); 
        curl_setopt($ch, CURLOPT_URL,"https://api.renren.com/restserver.do"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
    
    /**
     * 上传图片
     */
     
    function photoPost($photo,$description = '',$access_token,$page_id,$album = ''){
        $data = array(
            'upload' => '@'.APP_PATH.$photo,
            'caption' => $description,
            'access_token' => $access_token,
            'page_id' => $page_id,
            'v' => '1.0',
            'format' => 'json',
            'method' => 'photos.upload'
            );
        /*
        $data = array(
            'file' => '@'.APP_PATH.$photo,
            'description' => $description,
            'access_token' => $access_token,
            'page_id' => $page_id,
            );*/
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://api.renren.com/restserver.do");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $result = curl_exec ($ch);
        $result = json_decode($result); 
        curl_close ($ch);
        return $result;
    }
}
?>