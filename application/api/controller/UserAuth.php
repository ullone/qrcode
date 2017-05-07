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
  private $state;
  public function __construct () {
    if(!empty($_GET['user_code'])){
      //应用没有app_code
      $this->callBackUserInfo($_GET['user_code'], $this->state);
    }else {
      echo $this->getOpenId();
    }
    if(empty($_SERVER['HTTP_REFERER']))
      $this->state = null;
    else $this->state = $_SERVER['HTTP_REFERER'];
  }
  private function callBackUserInfo($user_code,$last_url){
  //返回uid给应用
    $returnInfo = new ReturnUserInfo($user_code,$last_url);
    if(!$returnInfo->index()){
      $this->getOpenId();
    }
  }
  private function getOpenId(){
    $options = [
      'debug'    => true,
      'app_id'   => 'wxfb396a8777e67439',
      'secret'   => '758831403d20fecd8b0ac6334779b3a4',
      // 'app_id'   => 'wx860c23f43a2de53a',
      // 'secret'   => '6841c7cc7f6e83b413f0fe611ae91ff2',
      'token'    => 'mateor1newlif2cjiumeng3',
      // 'state'    => 'test',
      'log'      => [
        'level'  => 'debug',
        'file'   => '/tmp/easywechat.log'
      ],
    ];
    //
    // echo 'success';
    $app   = new Foundation\Application($options);
    if(empty($_GET['code'])){
      $response = $app->oauth->scopes(['snsapi_base'])->redirect($this->state);
      $response->send();
    }
    $user = $app->oauth->user();
    $operate = new Operate($user->getId());
    $operate->index();
  }
}
