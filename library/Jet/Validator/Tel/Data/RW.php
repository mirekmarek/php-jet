<?php

return [
	"prefix"   => "+250",
	"patterns" => [
		"general"   => "/^[027-9]\\d{7,8}\$/",
		"fixed"     => "/^(?:2[258]\\d{7}|06\\d{6})\$/",
		"mobile"    => "/^7[238]\\d{7}\$/",
		"toll_free" => "/^800\\d{6}\$/",
		"premium"   => "/^900\\d{6}\$/",
		"emergency" => "/^112\$/"
	]
];