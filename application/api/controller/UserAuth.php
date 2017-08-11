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

  private $openid;
  private $options;
  private $state;
  public function __construct () {
    // $this->authCheck();
    $this->set();
    $this->getQrcode();
    // $this->setMenu();
  }

  private function set () {
    $this->options = [
      'debug'    => true,
      //测试服务号
      // 'app_id'   => 'wxfb396a8777e67439',
      // 'secret'   => '758831403d20fecd8b0ac6334779b3a4',
      'app_id'   => 'wx1088ddeead7c4aa7',
      'secret'   => 'f8779402d2d919717ae7fe8a4fc26230',
      'token'    => 'qrcodetest',
      // 'state'    => 'test',
      'log'      => [
        'level'  => 'debug',
        'file'   => '/tmp/easywechat.log'
      ],
    ];
  }

  private function authCheck () {
    if(empty($_GET['url']))
      $this->state = 'null';
    else $this->state = $_GET['url'];
    if(!empty($_GET['user_code'])){
      //应用有app_code
      $this->callBackUserInfo($_GET['user_code'], $this->state);
    }
    // else if(!(empty($_GET['menu_set']))){
    //   if($_GET['menu_set'] == 1){
    //     $this->setMenu();
    //   }
    // }
    else {
      echo $this->getOpenId();
      // $this->setMenu();
    }
  }
  private function callBackUserInfo($user_code,$last_url){
    //返回uid给应用
    $returnInfo = new ReturnUserInfo($user_code,$last_url);
    if(!$returnInfo->index()){
      $this->getOpenId();
    }
  }

  private function getOpenId(){
    //获取openid
    $app   = new Foundation\Application($this->options);
    if(empty($_GET['code'])){
      $response = $app->oauth->scopes(['snsapi_base'])->redirect($this->state);
      $response->send();
    }
    $user = $app->oauth->user();
    //执行跳转，重定向操作
    $operate = new Operate($user->getId());
    $operate->index();
  }

  private function getQrcode () {
    $app = new Foundation\Application($this->options);
    $qrcode = $app->qrcode;
    $result = $qrcode->temporary(56, 6 * 24 * 3600);
    $ticket = $result->ticket;// 或者 $result['ticket']
    $expireSeconds = $result->expire_seconds; // 有效秒数
    $url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
    // var_dump($url);die ;
    // echo 'url:'.$url;
    // echo 'ticket:'.$ticket;
    echo '<img src="'.$qrcode->url($ticket).'">';
  }

  public function setMenu(){
    $app   = new Foundation\Application($this->options);
    if(empty($_GET['code'])){
      $response = $app->oauth->scopes(['snsapi_base'])->redirect($this->state);
      $response->send();
    }
    $menu = $app->menu;
    $buttons = [
      [
          "type" => "view",
          "name" => "商城",
          "url" => "http://dxzshop.cjiumeng.com/"
      ],
      [
          "type" => "view",
          "name" => "社区",
          "url" => "http://dxzchat.cjiumeng.com/"
      ],
      [
          "type" => "view",
          "name" => "关于我们",
          "url" => "http://dxzshop.cjiumeng.com/?feat=default"
      ],
    ];
    $menu->add($buttons);

  }
}
