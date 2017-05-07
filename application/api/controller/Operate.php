<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cookie;
  use think\Cache;

  class Operate{
    private $openid;
    public function __construct ($openid = NULL) {
      $this->openid = $openid;
      Cache::connect(config('cache'));
    }

    public function index(){

    }

    private function newUser($openid){
      Db::table('user')->insert([
        'open_id' => $openid,
        'register_time' => $this->getTime();
      ]);
    }

    private function updateCacheAndCookie(){
      $newUser_code = randStr();
      Cache::set('userId_'.$newUser_code,$user_data);
      cookie::set('code',$newUser_code);
    }

    private function getTime(){
      date_default_timezone_set('PRC');
      return date('Y/m/d H:i:s',$_SERVER['REQUEST_TIME']);
    }
  }
