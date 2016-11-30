<?php
if($_SERVER['QUERY_STRING']=='install'){
	header("Content-type:text/xml; Charset=utf-8");
	echo file_get_contents(__DIR__ . '/Release.xml');
}elseif($_SERVER['QUERY_STRING']=='beta'){
	header('Content-Type: text/plain; charset=utf-8');
	echo file_get_contents(__DIR__ . '/beta.html');
}elseif($_SERVER['QUERY_STRING']!=''){
	$s=__DIR__ . '/' . str_replace('\\','/',$_SERVER['QUERY_STRING']);
	if(is_readable($s) && strpos($s,'./')===false){
		header('Content-Type: application/octet-stream');
		echo file_get_contents($s);
	}elseif(is_readable($s)==false){
		$regex_otherver= '/[0-9]{6}-[0-9]{6}\.xml/i';
		$xml = __DIR__ . '/' . 'builds.xml';
		$xml = file_get_contents($xml);
	    $xml = @simplexml_load_string($xml);
	    reset($xml->build);
	    $v1='150101';//current($xml->build);
	    $v2=end($xml->build);
        if(preg_match( $regex_otherver, str_replace('\\','/',$_SERVER['QUERY_STRING']) ) != 0 ){
			header('Content-Type: application/octet-stream');
			echo file_get_contents(__DIR__ . '/' . $v1 . '-' . $v2 . '.xml');
        }
	}
}else{
	header('Content-Type: text/plain; charset=utf-8');
	echo file_get_contents(__DIR__ . '/now.html');
}