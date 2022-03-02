<?php

$old = 170000;
$php = '5.2';
$channel_name = 'normal';

$isbeta = false;
if(strpos(__DIR__,'/beta')!==false){
  $isbeta = true;
  $channel_name = 'beta';
}

$isalpha = false;
if(strpos(__DIR__,'/alpha')!==false){
  $isalpha = true;
  $channel_name = 'alpha';
}

if (is_readable('./build.json')) {
    $json = json_decode(file_get_contents('./build.json'));
} elseif (is_readable('../build.json')) {
    $json = json_decode(file_get_contents('../build.json'));
} elseif (is_readable('../../build.json')) {
    $json = json_decode(file_get_contents('../../build.json'));
}

$target = new stdClass;
$source = new stdClass;
$result = new stdClass;

foreach ($json->builds as $key => $value) {
  if(stripos($value->channel, 'normal') !== false){
    $source->build = $value->version;
    $source->name = $value->name;
    $old = $source->build;
    $source->php = $php;
    break;
  }
}

$s = $_SERVER['HTTP_USER_AGENT'];
if (stripos($s,'ZBlogPHP')!==false){

  if (preg_match('/ZBlogPHP\/([0-9]+)/i', $s, $m) == 1) {
     $old = $m[1];
  }
  if (preg_match('/PhpVer\/([0-9.]+)/i', $s, $m) == 1) {
     $php = $m[1];
  }
  foreach ($json->builds as $key => $value) {
    if(stripos($value->channel, $channel_name) !== false && (int)$value->version <= (int) $old){
      $source->build = $value->version;
      $source->name = $value->name;
    }
  }

  $source->php = $php;
}

foreach ($json->builds as $key => $value) {
  if(stripos($value->channel, $channel_name) !== false && version_compare($php, $value->php) >= 0){
      $target->build = $value->version;
      $target->name = $value->name;
      $target->php = $value->php;
  }
}
$result->source = $source;
$result->target = $target;
$result->isbeta = $isbeta;
$result->isalpha = $isalpha;

echo json_encode($result);