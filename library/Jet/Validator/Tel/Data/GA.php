<?php

return [
	"prefix"   => "+241",
	"patterns" => [
		"general"   => "/^[01]\\d{6,7}\$/",
		"fixed"     => "/^1\\d{6}\$/",
		"mobile"    => "/^0[2-7]\\d{6}\$/",
		"emergency" => "/^(?:1730|18|13\\d{2})\$/"
	]
];