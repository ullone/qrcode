<?php
namespace app\api\controller;

header("Content-Type:text/html; charset=utf-8");

require_once __DIR__ . '/../../../extend/easywechat/autoload.php';
// include '/webdata/userAPI/vendor/autoload.php';

use think\Db;
use think\Cookie;
use think\Cache;
use app\index\controller;
// use EasyWeChat\Foundation\Application;
use EasyWeChat\Foundation as Foundation;

class UserAuth {
  public function __construct () {
    $options = [];
    $app         = new Foundation\Application($options);
    // 获取 access token 实例
    $accessToken = $app->access_token; // easywechat\Core\AccessToken 实例
    $token = $accessToken->getToken(); // token 字符串
    $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
    // $userService = $app->user;
    // $user        = $userService->get($openId);
    echo $user->token;
  }
}
