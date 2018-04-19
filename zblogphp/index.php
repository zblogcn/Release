<?php
if($_SERVER['QUERY_STRING']=='install'){
	header("Content-type:text/xml; Charset=utf-8");
	echo file_get_contents(__DIR__ . '/Release.xml');
}elseif($_SERVER['QUERY_STRING']=='beta'){
	header('Content-Type: text/plain; charset=utf-8');
	echo trim(file_get_contents(__DIR__ . '/beta.html'));
}elseif($_SERVER['QUERY_STRING']!=''){
	$s=__DIR__ . '/' . str_replace('\\','/',$_SERVER['QUERY_STRING']);
	if(is_readable($s) && strpos($s,'./')===false){
		//如果是？
		$regex_otherver= '/^([0-9]{6})-([0-9]{6})\.xml$/i';
        if(preg_match( $regex_otherver, str_replace('\\','/',$_SERVER['QUERY_STRING']) ) != 0 ){

        }
		header('Content-Type: application/octet-stream');
		echo file_get_contents($s);
	}elseif(is_readable($s)==false){
		$regex_otherver= '/^([0-9]{6})-([0-9]{6})\.xml$/i';
        if(preg_match( $regex_otherver, str_replace('\\','/',$_SERVER['QUERY_STRING']) ) != 0 ){

			$a = array();
			preg_match_all($regex_otherver,str_replace('\\','/',$_SERVER['QUERY_STRING']),$a);

			$v0 = (int) $a[1][0];
			$v1 = '';
			$v2 = (int) $a[2][0];

			$xml = __DIR__ . '/' . 'builds.xml';
			$xml = file_get_contents($xml);
			$xml = @simplexml_load_string($xml);

			foreach ($xml->xpath('//builds/build') as $key => $value) {
				if ((int)$value > $v0){
					break;
				}
				$v1 = (int)$value;
			}

			header('Content-Type: application/octet-stream');
			echo file_get_contents(__DIR__ . '/' . $v1 . '-' . $v2 . '.xml');
        }
	}
}else{
	header('Content-Type: text/plain; charset=utf-8');
	echo trim(file_get_contents(__DIR__ . '/now.html'));
}
