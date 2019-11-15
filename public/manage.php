<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 
// [ 应用入口文件 ]
namespace think;

// 定义应用目录
define('APP_PATH', __DIR__ . '/Admin/');
//命名空间
define('APP_NAMESPACE','manage');
// 绑定到index模块
define('BIND_MODULE','manage');

define('WEB_PATH', __DIR__ . '/');
define('RUNTIME_PATH', WEB_PATH . './runtime');
// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
