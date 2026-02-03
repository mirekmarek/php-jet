<?php

return [
	"prefix"   => "+500",
	"patterns" => [
		"general"   => "/^[2-7]\\d{4}\$/",
		"fixed"     => "/^[2-47]\\d{4}\$/",
		"mobile"    => "/^[56]\\d{4}\$/",
		"shortcode" => "/^1\\d{2}\$/",
		"emergency" => "/^999\$/"
	]
];