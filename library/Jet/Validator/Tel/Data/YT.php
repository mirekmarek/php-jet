<?php

return [
	"prefix"   => "+262",
	"patterns" => [
		"general"   => "/^[268]\\d{8}\$/",
		"fixed"     => "/^2696[0-4]\\d{4}\$/",
		"mobile"    => "/^639\\d{6}\$/",
		"toll_free" => "/^80\\d{7}\$/",
		"emergency" => "/^1(?:12|5)\$/"
	]
];