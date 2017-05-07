<?php
namespace app\api\controller;

header("Content-Type:text/html; charset=utf-8");

require_once __DIR__ . '/../../../extend/autoload.php';
// include '/webdata/userAPI/vendor/autoload.php';

use think\Db;
use think\Cookie;
use think\Cache;
use app\index\controller;
// use EasyWeChat\Foundation\Application;
use EasyWeChat\Foundation as Foundation;

class UserAuth {

  public $openid;
  public function __construct () {
    $options = [
      'debug'    => true,
      'app_id'   => 'wxfb396a8777e67439',
      'secret'   => '758831403d20fecd8b0ac6334779b3a4',
      // 'app_id'   => 'wx860c23f43a2de53a',
      // 'secret'   => '6841c7cc7f6e83b413f0fe611ae91ff2',
      'token'    => 'mateor1newlif2cjiumeng3',
      'log'      => [
        'level'  => 'debug',
        'file'   => '/tmp/easywechat.log'
      ],
    ];
    //
    // echo 'success';
    $app   = new Foundation\Application($options);
    // 获取 access token 实例
    $accessToken = $app->access_token; // EasyWeChat\Core\AccessToken 实例
    $token = $accessToken->getToken(); // token 字符串
    $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
    echo $accessToken.'+'.$token;

  }
}
