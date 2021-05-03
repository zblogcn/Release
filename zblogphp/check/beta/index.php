<?php

$isbeta = false;
if(strpos(__DIR__,'/beta')!==false)
  $isbeta = true;

if (is_readable('../../build.json')) {
  $json = json_decode(file_get_contents('../../build.json'));
}
elseif (is_readable('../build.json')) {
  $json = json_decode(file_get_contents('../build.json'));
}
elseif (is_readable('./build.json')) {
  $json = json_decode(file_get_contents('./build.json'));
}

$target = new stdClass;
$source = new stdClass;
$result = new stdClass;

foreach ($json->builds as $key => $value) {
  if(stripos($value->beta, 'beta') !== false){
    $source->build = $value->version;
    $source->name = $value->name;
    break;
  }
}

$old = 130707;

$s = $_SERVER['HTTP_USER_AGENT'];
if (stripos($s,'ZBlogPHP')!==false){
  $i = stripos($s,'ZBlogPHP');
  $old = substr($s, $i+9,7);
  $old = explode(' ',$old);
  $old = $old[0];
  foreach ($json->builds as $key => $value) {
    if(stripos($value->beta, 'beta') !== false && (int)$value->version <= (int) $old){
      $source->build = $value->version;
      $source->name = $value->name;
    }
  }
}

foreach ($json->builds as $key => $value) {
  if(stripos($value->beta, 'beta') !== false){
    if ($old < 162200) {
      $target->build = '162210';
      $target->name = '1.6.8 Valyria';
    } else {
      $target->build = $value->version;
      $target->name = $value->name;
    }
  }
}
$result->source = $source;
$result->target = $target;
$result->isbeta = $isbeta;

echo json_encode($result);