<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,
	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'Admin UI',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [],

	'signals_callbacks' => [
		'/test/received' => 'testReceived',
		'/test/multiple' => [
			'testMultiple1',
			'testMultiple2'
		],
	],

];