<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'Login page - Administration',

	'description' => '',

	'is_mandatory' => true,

	'pages'        => [
		'admin'    => [
			'change_password' => [
				'title' => 'Change password',
				'relative_path_fragment' => 'change-password',
				'contents' => [
					[
						'controller_action' => 'change_password'
					]
				]
			]
		]
	]

];