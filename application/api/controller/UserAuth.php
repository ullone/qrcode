<?php
namespace app\api\controller;

header("Content-Type:text/html; charset=utf-8");

include __DIR__ . '/../../vendor/autoload.php';

use think\Db;
use think\Cookie;
use think\Cache;
use app\index\controller;
use app\easywechat\Foundation\Application;

class UserAuth {
  public function __construct () {
    $app         = new Application($options);
    $userService = $app->user;
    $user        = $userService->get($openId);
    echo $user->nickname;
  }
}
