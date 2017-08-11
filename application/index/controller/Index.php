<?php
namespace app\index\controller;

use app\api\controller\Test;
use app\api\controller\UserAuth;
use app\api\controller\CheckToken;
use \think\View;

class Index
{
    public function index() {
      // if(empty($_GET['nonce']) && empty($_GET['signature']) && empty($_GET['echostr'])){
      //   $test = new UserAuth();
      // } else
      // $check = new CheckToken();
      if(empty($_GET))
        $test = new Test();
      else
        $test = new UserAuth();
    }
}
