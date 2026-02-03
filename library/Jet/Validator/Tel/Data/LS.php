<?php

return [
	"prefix"   => "+266",
	"patterns" => [
		"general"   => "/^[2568]\\d{7}\$/",
		"fixed"     => "/^2\\d{7}\$/",
		"mobile"    => "/^[56]\\d{7}\$/",
		"toll_free" => "/^800[256]\\d{4}\$/",
		"emergency" => "/^11[257]\$/"
	]
];