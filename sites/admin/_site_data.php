<?php
return [
	'name' => 'Example Administration',
	'is_secret' => true,
	'is_active' => true,
	'default_locale' => 'cs_CZ',
	'SSL_required' => false,
	'localized_data' => [
		'cs_CZ' => [
			'is_active' => true,
			'title' => 'PHP Jet',
			'URLs' => [
				'jet.lc/admin/',
			]
		],
		'en_US' => [
			'is_active' => true,
			'title' => 'PHP Jet',
			'URLs' => [
				'jet.lc/en/admin/',
			]
		],
	],
];
