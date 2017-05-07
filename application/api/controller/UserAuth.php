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
    $this->index();
  }

  public function index () {
    $this->userInfo();
  }

  private function userInfo () {
    $options = [
      'debug'    => true,
      'app_id'   => 'wxfb396a8777e67439',
      'secret'   => '758831403d20fecd8b0ac6334779b3a4',
      'token'    => 'mateor1newlif2cjiumeng3',
      'log'      => [
        'level'  => 'debug',
        'file'   => '/tmp/easywechat.log'
      ],
    ];
    $app = new Foundation\application($options);
    $server = $app->server;
    $user = $app->user;

    $server->setMessageHandler(function($message) use ($user) {
    $fromUser = $user->get($message->FromUserName);

      return "{$fromUser->nickname} 您好！欢迎关注 overtrue!";
    });

    $server->serve()->send();
  }
}
