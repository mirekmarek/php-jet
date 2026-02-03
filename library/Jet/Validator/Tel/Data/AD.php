<?php

return [
	"prefix'   => '+376',
	'patterns' => [
		'general'   => '/^(?:[346-9]|180)\\d{5}\$/',
		'fixed'     => '/^[78]\\d{5}\$/',
		'mobile'    => '/^[346]\\d{5}\$/',
		'toll_free' => '/^180[02]\\d{4}\$/',
		'premium'   => '/^9\\d{5}\$/',
		'emergency' => '/^11[0268]\$/'
	]
];