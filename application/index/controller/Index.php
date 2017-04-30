<?php
namespace app\index\controller;

use app\api\controller\UserAuth;
use \think\View;

class Index
{
    public function index()
    {
      // $test = new UserAuth();
      // echo 'success';
      $check = new CheckToken();
    }
}
