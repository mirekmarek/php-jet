<?php
use Jet\SysConf_Path;
use Jet\SysConf_URI;

require_once SysConf_Path::getLibrary().'Jet/SysConf/URI.php';



if(isset( $_SERVER['REQUEST_URI'] )) {
	$base_URI = $_SERVER['REQUEST_URI'];
	if(
		strpos($base_URI, '.') ||
		strpos($base_URI, '?')
	) {
		$base_URI = substr( $base_URI, 0, strrpos($base_URI, '/')).'/';
	}
} else {
	$base_URI = '/_tools/studio/';
}

SysConf_URI::setBase($base_URI);
SysConf_URI::setCss($base_URI.'css/');
SysConf_URI::setJs($base_URI.'js/');
SysConf_URI::setImages($base_URI.'images/');

