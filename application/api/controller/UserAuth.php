<?php
namespace app\api\controller;

header("Content-Type:text/html; charset=utf-8");

// include '/webdata/userAPI/vendor/autoload.php';

use think\Db;
use think\Cookie;
use think\Cache;
use app\index\controller;
use app\EasyWechat\Foundation\Application;

class UserAuth {
  public function __construct () {
    $options = [];
    $app         = new Application($options);
    // 获取 access token 实例
    $accessToken = $app->access_token; // EasyWechat\Core\AccessToken 实例
    $token = $accessToken->getToken(); // token 字符串
    $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
    // $userService = $app->user;
    // $user        = $userService->get($openId);
    echo $user->token;
  }
}
