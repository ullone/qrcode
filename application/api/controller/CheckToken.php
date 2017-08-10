<?php
namespace app\api\controller;

use think\Db;

header("Content-Type:text/html; charset=utf-8");

class CheckToken
{
    public function __construct (){
      $nonce     = $_GET['nonce'];
      $token     = 'qrcodetest';
      $timestamp = $_GET['timestamp'];
      $echostr   = $_GET['echostr'];
      $signature = $_GET['signature'];
      //形成数组，然后按字典序排序
      $array = array();
      $array = array($nonce, $timestamp, $token);
      sort($array);
      //拼接成字符串,sha1加密 ，然后与signature进行校验
      $str = sha1( implode( $array ) );
      if( $str == $signature && $echostr ){
          //第一次接入weixin api接口的时候
          echo  $echostr;
        }
    }
}
