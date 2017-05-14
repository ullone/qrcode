<?php
namespace app\index\controller;

use app\api\controller\UserAuth;
use app\api\controller\CheckToken;
use \think\View;

class Index
{
    public function index() {
      $check = new CheckToken();
      $test = new UserAuth();

    }
}
