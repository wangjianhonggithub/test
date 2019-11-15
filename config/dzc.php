<?php
// 公共配置文件
return [
    // 文件库地址
    'document_path'   => '/uploads/',

    // 协议类型
    'http_type' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://',
    // 'http_type' => 'https://',

];
