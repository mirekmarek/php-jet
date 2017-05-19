<?php
return [
	'database' => [
		'default_connection_name' => 'default',
		'connections' => [
			'default' => [
				'driver' => 'mysql',
				'name' => 'default',
				'DSN' => 'host=localhost;port=3306;dbname=jet;charset=utf8',
				'username' => 'jet',
				'password' => 'jet',
			],
		],
	],
	'data_model' => [
		'backend_type' => 'MySQL',
		'backend_options' => [
			'connection_read' => 'default',
			'connection_write' => 'default',
			'engine' => 'InnoDB',
			'default_charset' => 'utf8',
			'collate' => 'utf8_general_ci',
		],
	],
	'emails' => [
		'senders' => [
			'en_US' => [
				'email' => 'mirek.marek.2m@gmail.com',
				'name' => 'Miroslav Marek',
			],
			'cs_CZ' => [
				'email' => 'mirek.marek.2m@gmail.com',
				'name' => 'Miroslav Marek',
			],
		],
	],
];
