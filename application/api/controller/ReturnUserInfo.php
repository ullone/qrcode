<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cache;

  class ReturnUserInfo{
    private $user_code;

    public function __construct ($user_code = NULL) {
      $this->user_code = $user_code;
      Cache::connect(config('cache'));
      $userInfo = Cache::get('userId_'.$this->user_code);
    }

    public function index(){
      $userInfo = Cache::get('userId_'.$this->user_code);
      if(empty($userInfo)){
        return false;
      }
      callBack(0,'成功获取用户信息',$userInfo);
    }
  }
