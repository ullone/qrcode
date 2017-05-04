<?php
namespace app\index\controller;

use app\api\controller\UserAuth;
use app\api\controller\CheckToken;
use \think\View;

class Index
{
    public function index() {
      $test = new UserAuth();
      // echo $test;
      // echo 'success';
      // $check = new CheckToken();
    }
}
