<?php
return [
	'name' => 'Example REST API',
	'is_secret' => true,
	'is_default' => false,
	'is_active' => true,
	'default_locale' => 'cs_CZ',
	'SSL_required' => false,
	'localized_data' => [
		'cs_CZ' => [
			'is_active' => true,
			'URLs' => [
				'jet.lc/rest/',
			]
		],
		'en_US' => [
			'is_active' => true,
			'URLs' => [
				'jet.lc/en/rest/',
			]
		],
	],
];
