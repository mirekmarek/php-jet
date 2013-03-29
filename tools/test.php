<?php
$time = floor(microtime(true) * 1000);

$unique_ID = uniqid("", true);

$u_name = substr(php_uname("n"), 0,14);

$ID = $u_name.$time .$unique_ID;

$ID = preg_replace("~[^a-zA-Z0-9]~", "_", $ID);

var_dump($u_name, (string)$time, $unique_ID, $ID);