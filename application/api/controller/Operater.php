<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cookie;
  use think\Cache;

  class Operater{
    private $openid;
    public function __construct ($openid = NULL) {
      $this->openid = $openid;
    }

    private function newUser($openid){

    }

    private function updateCacheAndCookie(){

    }

    
  }
