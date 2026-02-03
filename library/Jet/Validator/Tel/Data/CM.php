<?php

return [
	"prefix"   => "+237",
	"patterns" => [
		"general"   => "/^[237-9]\\d{7}\$/",
		"fixed"     => "/^(?:22|33)\\d{6}\$/",
		"mobile"    => "/^[79]\\d{7}\$/",
		"toll_free" => "/^800\\d{5}\$/",
		"premium"   => "/^88\\d{6}\$/",
		"emergency" => "/^1?1[37]\$/"
	]
];