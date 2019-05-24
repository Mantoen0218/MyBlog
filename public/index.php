<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

define('ROOT_PATH', __DIR__ . '/../');

define('ROOT', 'http://localhost/blog/');

define('ASSETS', ROOT . 'public/static/assets');

define('BLOG', ROOT . 'public/static/blog');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
