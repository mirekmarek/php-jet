<?php

return [
	"prefix"   => "+505",
	"patterns" => [
		"general"   => "/^[128]\\d{7}\$/",
		"fixed"     => "/^2\\d{7}\$/",
		"mobile"    => "/^[578]\\d{7}\$/",
		"toll_free" => "/^1800\\d{4}\$/",
		"emergency" => "/^118\$/"
	]
];