<?php
session_start();
define('WECHAT_Token', 'V3QaWlqxhe8Yhv0BYnRpCu84inMq4VSZ');
define('WECHAT_AppSecret', 'f8bc3a5c45ef9376d6de1423ae54b30c');
define('WECHAT_AppID', 'wxf6071ab249f4c7f4');
define('WECHAT_EncodingAESKey', 'wxf6071ab249f4c7f4');

define('WECHAT_ID', 'gh_9634df8f5354');

define('CY_SQL_PORT', '3306');
define('CY_SQL_USERNAME', 'fzuhelper_db');
define('CY_SQL_PASSWORD', 'WGdQxrefsSwzfeKG');
define('CY_SQL_DATABASE', 'fzuhelper_db');

define('CY_PREFIX', 'fzuhelper_');
define('CY_CHARSET', 'utf8');

if($_SERVER['HTTP_HOST'] == 'fzuhelper.localhost.com'){
    define('CY_MEMCACHE_HOST', '192.168.95.131');
    define('CY_SQL_HOST', 'qcloud.cloudyu.me');
    define('CY_DEBUG', true);

}else{
    define('CY_MEMCACHE_HOST', 'localhost');
    define('CY_SQL_HOST', 'localhost');
    define('CY_DEBUG', true);
}

define('CY_MEMCACHE_PORT', 11211);
Global $memcache;
$memcache = new Memcache;
if(!$memcache->addServer(CY_MEMCACHE_HOST, CY_MEMCACHE_PORT)){
    die('memcached服务连接失败');
}
?>