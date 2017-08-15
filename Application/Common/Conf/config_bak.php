<?php
return array(
        /* 数据库设置 */
        'DB_TYPE' => 'mysql',
        'DB_HOST' => '112.74.65.233',
        'DB_PORT' => '3306',
        'DB_NAME' => 'foodcase',
        'DB_USER' => 'foodcase',
        'DB_PWD' => 'foodcase2015',
        'DB_PREFIX' => 'fd_',
        'debug'=>FALSE,
//        'appid'=>'wx1fafb24fac7f9f9c', //深圳美到家 资料
//        'appsecret'=>'b519ba75818287398e030087354cd3d8',
        'appid'=>'wxa3ff4d481b0ead5b', //美到家商户版资料
        'appsecret'=>'cd5d7457307269f139710f4f60524ca7',
        'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
);