<?php
namespace app\index\controller;

use app\api\controller\UserAuth;
use app\api\controller\CheckToken;
use EasyWeChat\Foundation as Foundation;
use \think\View;

class Index
{
    public function index() {
      // if(empty($_GET['nonce']) && empty($_GET['signature']) && empty($_GET['echostr'])){
      //   $test = new UserAuth();
      // } else
      // $check = new CheckToken();
      $app = new Foundation\Application($this->options);
      $server = $app->server;
      $server->setMessageHandler(function ($message) {
          return "您好！欢迎关注我!";
      });
      $server->serve()->send();
    }
}
