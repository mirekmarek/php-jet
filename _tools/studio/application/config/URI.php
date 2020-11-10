<?php
use Jet\SysConf_PATH;
use Jet\SysConf_URI;

require_once SysConf_PATH::LIBRARY().'Jet/SysConf/URI.php';



if(isset( $_SERVER['REQUEST_URI'] )) {
	$base_URI = $_SERVER['REQUEST_URI'];
	if(
		strpos($base_URI, '.') ||
		strpos($base_URI, '?')
	) {
		$base_URI = dirname($base_URI).'/';
	}
} else {
	$base_URI = '/_tools/studio/';
}

SysConf_URI::setBASE($base_URI);
SysConf_URI::setPUBLIC($base_URI.'public/');
