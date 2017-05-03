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
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
//定义命名空间
// \think\Loader::addNamespace([
//     'EasyWeChat' => '/../application/easywechat/overtrue/wechat/src',
//     'Doctrine'   => '/../application/easywechat/doctrine/cache/lib/Doctrine',
//     'Monolog'    => '/../application/easywechat/monolog/monolog/src/Monolog',
//     'Pimple'     => '/../application/easywechat/pimple/pimple/src/Pimple',
//     'Symfony'    => '/../application/easywechat/symfony/http-foundation'
// ]);
\think\Loader::addNamespace('abc','../extend/easywechat');
