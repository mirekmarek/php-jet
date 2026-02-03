<?php

return [
	"prefix"   => "+371",
	"patterns" => [
		"general"   => "/^[2689]\\d{7}\$/",
		"fixed"     => "/^6[3-8]\\d{6}\$/",
		"mobile"    => "/^2\\d{7}\$/",
		"toll_free" => "/^80\\d{6}\$/",
		"premium"   => "/^90\\d{6}\$/",
		"shared"    => "/^81\\d{6}\$/",
		"emergency" => "/^(?:0[123]|112)\$/"
	]
];