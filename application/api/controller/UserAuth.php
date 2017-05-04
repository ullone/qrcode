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
  public $me;
  public function __construct () {
    $options = [
      'debug'    => true,
      'app_id'   => 'wx860c23f43a2de53a',
      'secret'   => '6841c7cc7f6e83b413f0fe611ae91ff2',
      'token'    => 'cjiumeng123',
      'log'      => [
        'level'  => 'debug',
        'file'   => '/tmp/easywechat.log'
      ],
    ];
    //
    $app = new Foundation\Application($options);
    // 从项目实例中得到服务端应用实例。
    $server = $app->server;
    $server->setessageHandler(function ($message) {
        // $message->FromUserName // 用户的 openid
        // $message->MsgType // 消息类型：event, text....
        $this->me = "您好！欢迎关注我!";
    });
    // $mess = $server->getMessage();
    // return $mess['ToUserName'];
    // $response = $server->serve();
    // $response->send(); // Laravel 里请使用：return $response;
  }
}
