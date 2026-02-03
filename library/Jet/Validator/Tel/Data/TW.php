<?php

return [
	"prefix"   => "+886",
	"patterns" => [
		"general"   => "/^[2-9]\\d{7,8}\$/",
		"fixed"     => "/^[2-8]\\d{7,8}\$/",
		"mobile"    => "/^9\\d{8}\$/",
		"toll_free" => "/^800\\d{6}\$/",
		"premium"   => "/^900\\d{6}\$/",
		"emergency" => "/^11[029]\$/"
	]
];