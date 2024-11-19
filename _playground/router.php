<?php
$public = [
	'/css/',
	'/js/',
	'/images/',
	'/_tools/studio/css/',
	'/_tools/studio/js/',
	'/_tools/studio/images/',
];

foreach($public as $uri) {
	if( str_starts_with($_SERVER['REQUEST_URI'], $uri) ) {
		return false;
	}
}

if( str_starts_with( $_SERVER['REQUEST_URI'], '/_tools/studio/' ) ) {
	require dirname(__DIR__).'/_tools/studio/index.php';
	return true;
}

require dirname(__DIR__).'/application/bootstrap.php';

