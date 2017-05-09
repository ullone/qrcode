<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cache;

  class ReturnUserInfo{
    private $user_code;
    private $last_url;
    public function __construct ($user_code = NULL, $last_url = NULL) {
      $this->user_code = $user_code;
      $this->last_url = $last_url;
      Cache::connect(config('cache'));
      $userInfo = Cache::get('userId_'.$this->user_code);
    }

    public function index(){
      $userInfo = Cache::get('userId_'.$this->user_code);
      if(empty($userInfo)){
        return false;
      }
      $this->writeLog($userInfo);
      // callBack(0,'成功获取用户信息',$userInfo);
      echo $userInfo;
    }

    private function writeLog($uid){
      Db::table('look_log')->insert([
        'time' => $this->getTime(),
        'uid' => $uid,
        'app_url' => $this->last_url
      ]);
    }

    private function getTime(){
      date_default_timezone_set('PRC');
      return date('Y/m/d H:i:s',$_SERVER['REQUEST_TIME']);
    }
  }
