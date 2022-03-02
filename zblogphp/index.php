<?php

if (is_readable('./build.json')) {
    $json = json_decode(file_get_contents('./build.json'));
} elseif (is_readable('../build.json')) {
    $json = json_decode(file_get_contents('../build.json'));
} elseif (is_readable('../../build.json')) {
    $json = json_decode(file_get_contents('../../build.json'));
}

if($_SERVER['QUERY_STRING']=='install'){
    //不直读而是302跳转是要让cdn生效
    header('Location: https://update.zblogcn.com/zblogphp/Release.xml');die();

    //header("Content-type:text/xml; Charset=utf-8");
    //echo file_get_contents(__DIR__ . '/Release.xml');
}elseif($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!='beta' && $_SERVER['QUERY_STRING']!='alpha'){
    $s=__DIR__ . '/' . str_replace('\\','/',$_SERVER['QUERY_STRING']);
    if(is_readable($s) && strpos($s,'./')===false){
        //如果是xml文件
        if(substr($s, -4) == '.xml'){
            //不直读而是302跳转是要让cdn生效
            header('Location: https://update.zblogcn.com/zblogphp/' . str_replace('\\','/',$_SERVER['QUERY_STRING']));die();
        }

        header('Content-Type: application/octet-stream');
        $s = file_get_contents($s);
        echo $s;
        die;
    }elseif(is_readable($s) == false && strpos($s,'./')===false) {
        //如果是不存在的xml文件
        if(substr($s, -4) == '.xml'){
          $v = $_SERVER['QUERY_STRING'];
          if (preg_match('/([0-9]+)-([0-9]+)\.xml/i', $v, $m) == 1) {
            $v_old = '130707';//$m[1];
            $v_new = $m[2];
            $v = $v_old . '-' . $v_new . '.xml'; 
            header('Location: https://update.zblogcn.com/zblogphp/' . str_replace('\\','/',$v));die();
          }
        }
    }
    die;
}else{
    header('Content-Type: text/plain; charset=utf-8');

    if(array_key_exists('beta', $_GET) == false && array_key_exists('alpha', $_GET) == false){
      $version = '';
      foreach ($json->builds as $key => $value) {
        if(stripos($value->channel, 'normal') !== false)
            $version = $value->name . ' Build ' . $value->version;
      }
      echo $version;
    } elseif(array_key_exists('beta', $_GET) == true){
      $version = '';
      foreach ($json->builds as $key => $value) {
        if(stripos($value->channel, 'beta') !== false)
            $version = $value->name . ' Build ' . $value->version;
      }
      echo $version;
    } elseif(array_key_exists('alpha', $_GET) == true){
      $version = '';
      foreach ($json->builds as $key => $value) {
        if(stripos($value->channel, 'alpha') !== false)
            $version = $value->name . ' Build ' . $value->version;
      }
      echo $version;
    }
}
