<?php
if(preg_match('/'
		.'(^\/css\/)|'
		.'(^\/js\/)|'
		.'(^\/images\/)|'
		.'(^\/_tools\/studio\/)'
	.'/', $_SERVER['REQUEST_URI'])) {
	return false;
}

require dirname(__DIR__).'/application/bootstrap.php';

