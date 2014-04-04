<?php
$wx_config = include_once 'wx_config.php';
$config = array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 1,
    'URL_CASE_INSENSITIVE' =>false,//url大小写
    'URL_HTML_SUFFIX'=>'',//伪静态后缀
    'SITE_URL'  => 'http://page.kungg.com/App/',
    'ROOT_URL'  => 'http://page.kungg.com',
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
    'Secret_Key'    => 'asdt=02,,2sxxs1',
    /**
     * 
     */
    'DEFAULT_FILTER' => 'htmlspecialchars,strip_tags',
    /**
     * 人人开放平台
     */
    'RENREN_APP_KEY'    => '8da3d03b34794e0bb061a004056fff92',
    'RENREN_APP_SECRET' => '145859bc81974dab8b8eaeb34dd20c11',
    'RENREN_APP_ID'     => '235544',
    'RENREN_CALLBACK'   => 'Renren',
    'RENREN_SCOPE'      => 'read_user_album+read_user_feed+status_update+admin_page+photo_upload',
    'RENREN_CALLBACK_TWICE'   => 'Renren/reg_twice', //已经注册却没有绑定人人，使用回调地址
    'RENREN_UPLOAD_DIR' => APP_PATH.'pub/upload/',  //上传图片时的本地目录
    'RENREN_PUBLIC_TOKEN' => 'ksldjf9JMsd93Asdf', //人人网公众号TOKEN
    /**
     * 公众平台
     */
    //'WX_TOKEN'       => '90fdlmlwease233',
    'RENREN_TOKEN'   => 'ksldjf9JMsd93Asdf',
    
);
return array_merge($config, $wx_config);
?>