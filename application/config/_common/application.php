<?php
return [
	'database'      => [
		'default_connection_name' => 'default', 'connections' => [
			'default' => [
				'name'     => 'default', 'driver' => 'sqlite', 'DSN' => '/home/mirek/Jet/application/data/database.sq3',
				'username' => '', 'password' => '',
			],
		],
	], 'data_model' => [
		'backend_type' => 'SQLite', 'backend_options' => [
			'connection' => 'default',
		],
	], 'emails'     => [
		'senders' => [
			'en_US'    => [
				'email' => 'mirek.marek.2m@gmail.com', 'name' => 'Miroslav Marek EN',
			], 'cs_CZ' => [
				'email' => 'mirek.marek.2m@gmail.com', 'name' => 'Miroslav Marek CZ',
			],
		],
	],
];
