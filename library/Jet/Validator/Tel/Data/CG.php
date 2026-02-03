<?php

return [
	"prefix"   => "+242",
	"patterns" => [
		"general"   => "/^[028]\\d{8}\$/",
		"fixed"     => "/^222[1-589]\\d{5}\$/",
		"mobile"    => "/^0[14-6]\\d{7}\$/",
		"toll_free" => "/^800\\d{6}\$/"
	]
];