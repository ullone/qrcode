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
          $echoStr = $_GET["echostr"];
          $signature = $_GET["signature"];
          $timestamp = $_GET["timestamp"];
          $nonce = $_GET["nonce"];
          $token = "mateor1newlif2cjiumeng3";
          $tmpArr = array($token, $timestamp, $nonce);
          sort($tmpArr, SORT_STRING);
          $tmpStr = implode( $tmpArr );
          $tmpStr = sha1( $tmpStr );
          if( $tmpStr == $signature )
          {
            echo $echoStr;
            exit;
          }

          else
          {
            echo "validate没有通过";
          }

    }
}
