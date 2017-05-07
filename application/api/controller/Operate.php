<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cookie;
  use think\Cache;

  class Operate{
    private $openid;
    private $user_code;
    public function __construct ($openid = NULL) {
      $this->openid = $openid;
      Cache::connect(config('cache'));
    }

    public function index(){
      if(empty($this->openid)){
        callBack(101,'没有认证的用户');
      }
      $uid = Db::table('user')->where('open_id',$this->openid)->field('id')->find()['id'];
      if(empty($uid)){
        $this->newUser($this->openid);
        $uid = Db::table('user')->where('open_id',$this->openid)->field('id')->find()['id'];
      }
      $this->updateCacheAndCookie($uid);

      $this->headerUrl();
    }

    private function newUser($openid){
      Db::table('user')->insert([
        'open_id' => $openid,
        'register_time' => $this->getTime()
      ]);
    }

    private function updateCacheAndCookie($user_data){
      $new_user_code = randStr(32);
      $this->user_code = $new_user_code;
      Cache::set('userId_'.$new_user_code,$user_data);
      cookie::set('code',$new_user_code);
    }

    private function headerUrl(){
      $uri = $_GET['state'];
      if(empty($uri)){
        callBack(103,'来自未知的应用');
      }
      header('location: http://'.$uri.'?user_code='.$this->user_code);
    }

    private function getTime(){
      date_default_timezone_set('PRC');
      return date('Y/m/d H:i:s',$_SERVER['REQUEST_TIME']);
    }
  }
