<?php
$wx_config = include_once 'wx_config.php';
$config = array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 1,
    'URL_CASE_INSENSITIVE' =>false,//url大小写
    'URL_HTML_SUFFIX'=>'',//伪静态后缀
    'SITE_URL'  => '',
    'ROOT_URL'  => '',
    'Wait_Second_Csrf' => 3, //页面跳转等待时间
    /**
     * 数据库配置
     */
    'DB_PREFIX' => '',
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'wsyzrenc_gyyz', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    /**
     * Cookie配置
     */
    'COOKIE_EXPIRE' => 8*3600,
    'COOKIE_DOMAIN' => '.page.kungg.com',
    'COOKIE_PREFIX' => 'kungg_',
    'COOKIE_PATH'   => '/',
    /**
     * Session配置
     */
    'SESSION_AUTO_START' => TRUE,
    'SESSION_PATH'       => '/',
    /**
     * 加密用的Key
     */
    'Secret_Key'    => '',
    /**
     * 
     */
    'DEFAULT_FILTER' => 'htmlspecialchars,strip_tags',
    /**
     * 人人开放平台
     */
    'RENREN_APP_KEY'    => '',
    'RENREN_APP_SECRET' => '',
    'RENREN_APP_ID'     => '',
    'RENREN_CALLBACK'   => 'Renren',
    'RENREN_SCOPE'      => 'read_user_album+read_user_feed+status_update+admin_page+photo_upload',
    'RENREN_CALLBACK_TWICE'   => 'Renren/reg_twice', //已经注册却没有绑定人人，使用回调地址
    'RENREN_UPLOAD_DIR' => APP_PATH.'pub/upload/',  //上传图片时的本地目录
    'RENREN_PUBLIC_TOKEN' => '', //人人网公众号TOKEN
    /**
     * 公众平台
     */
<<<<<<< HEAD
    //'WX_TOKEN'       => '',
    'RENREN_TOKEN'   => '',
=======
    //'WX_TOKEN'       => '',
    'RENREN_TOKEN'   => '',
>>>>>>> e7c5a2b5518cca6501190b6a782697e75eaa907b
    
);
return array_merge($config, $wx_config);
?>
