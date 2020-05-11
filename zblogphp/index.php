<?php

$beta = array(
    '2020-3-26' => '1.6.0 Build 162090',
    '2000-1-1' => '1.5.2 Build 151935',
);
$now = array(
    '2020-3-26' => '1.6.0 Build 162090',
    '2000-1-1' => '1.5.2 Build 151935',
);

if($_SERVER['QUERY_STRING']=='install'){
    header('Location: https://update.zblogcn.com/zblogphp/Release.xml');die();

    header("Content-type:text/xml; Charset=utf-8");
    echo file_get_contents(__DIR__ . '/Release.xml');
}elseif($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!='beta'){
    $s=__DIR__ . '/' . str_replace('\\','/',$_SERVER['QUERY_STRING']);
    if(is_readable($s) && strpos($s,'./')===false){
        //如果是xml文件
        if(substr($s, -4) == '.xml'){
            header('Location: https://update.zblogcn.com/zblogphp/' . str_replace('\\','/',$_SERVER['QUERY_STRING']));die();
        }

        header('Content-Type: application/octet-stream');
        $s = file_get_contents($s);
        echo $s;
        die;
    }
}else{
    header('Content-Type: text/plain; charset=utf-8');

    if (is_readable('../../build.json')) {
      $json = json_decode(file_get_contents('../../build.json'));
    }
    if (is_readable('../build.json')) {
      $json = json_decode(file_get_contents('../build.json'));
    }
    if (is_readable('./build.json')) {
      $json = json_decode(file_get_contents('./build.json'));
    }

    if(array_key_exists('beta', $_GET)==true){
      $version = '';
      foreach ($json->builds as $key => $value) {
        if($value->beta == true)
            $version = $value->name . ' Build ' . $value->version;
      }
      echo $version;
    }

    if(array_key_exists('beta', $_GET)==false){
      $version = '';
      foreach ($json->builds as $key => $value) {
        if($value->beta == false)
            $version = $value->name . ' Build ' . $value->version;
      }
      echo $version;
    }

}
