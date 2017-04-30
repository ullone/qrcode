<?php
namespace app\api\controller;

header("Content-Type:text/html; charset=utf-8");

use think\Db;
use think\Cookie;
use think\Cache;
use app\index\controller;
use app\EasyWechat\Foundation\Application;

class UserAuth {
  public function __construct () {
    $app         = new Application($options);
    $userService = $app->user;
    $user        = $userService->get($openId);
    echo $user->nickname;
  }
}
