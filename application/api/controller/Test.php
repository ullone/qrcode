<?php
  namespace app\api\controller;

  use think\Db;
  use think\Cookie;
  use think\Cache;
  use EasyWeChat\Foundation as Foundation;

  class Test{
    private $options;
    private $user_code;
    public function __construct () {
      $this->set();
      $this->getQrcode();
    }

    public function set () {
      $this->options = [
        'debug'    => true,
        //测试服务号
        // 'app_id'   => 'wxfb396a8777e67439',
        // 'secret'   => '758831403d20fecd8b0ac6334779b3a4',
        'app_id'   => 'wx1088ddeead7c4aa7',
        'secret'   => 'f8779402d2d919717ae7fe8a4fc26230',
        'token'    => 'qrcodetest',
        // 'state'    => 'test',
        'log'      => [
          'level'  => 'debug',
          'file'   => '/tmp/easywechat.log'
        ],
      ];
    }

    public function getQrcode() {
      $app = new Foundation\Application($this->options);
      $qrcode = $app->qrcode;
      $result = $qrcode->temporary(56, 6 * 24 * 3600);
      $ticket = $result->ticket;// 或者 $result['ticket']
      $expireSeconds = $result->expire_seconds; // 有效秒数
      $url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
      echo '<img src="'.$qrcode->url($ticket).'">';
    }
  }
