<?php
function callBack ($errno,$msg,$data = NULL) {
  exit (json_encode(
    array(
      'errno' => $errno,
      'msg'   => $msg,
      'data'  => $data
    ),JSON_UNESCAPED_UNICODE));
}

function debug ($data = NULL) {
  exit(var_dump($data));
}

function clearCookie ($name) {
  setcookie($name,'');
}

function array2Js ($data = array()) {
  json_encode($data,JSON_UNESCAPED_UNICODE);
}

function js2Array ($data = '') {
  json_decode($data,true);
}

function randStr($length, $type = 'all') {
  if ($type == 'all')
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  else if ($type == 'num')
    $chars='1234567890';
  else if ($type == 'upNum')
    $chars='QWERTYUIOPASDFGHJKLZXCVBNM0123456789';
  $str = "";
  for ($i = 0; $i < $length; $i++)
    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
  return $str;
}

function getTpl($fileName, $param = array()) {
  $tplPath = __DIR__ . "/index/view/" . $fileName . '.html';
  $tplContent = '';
  if(file_exists($tplPath))
    $tplContent = file_get_contents($tplPath);
  foreach ($param as $key => $value) {
    $key = '${' . $key . '}';
    $tplContent = str_replace($key, $value, $tplContent);
  }
  $newFileName = array();
  preg_match_all('/\${[A-z0-9]+}/', $tplContent, $newFileName, PREG_SET_ORDER);
  foreach ($newFileName as $newValue) {
    $newFileNamePath = $newValue[0];
    $newFileNamePath = substr(substr($newFileNamePath, 2), 0,  strlen($newFileNamePath)-3);
    if(file_exists(__DIR__ . "/index/view/" . $newFileNamePath . ".html"))
      $tplContent = str_replace('${' . $newFileNamePath . '}', getTpl($newFileNamePath, array()), $tplContent);
  }
  return $tplContent;
}
