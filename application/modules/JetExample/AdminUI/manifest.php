<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,
	'vendor' => 'Jet (example)',

	'label' => 'Admin UI',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [],

	'factory_overload_map' => [],

	'signals_callbacks' => [
		'/test/received' => 'testReceived',
		'/test/multiple' => [
			'testMultiple1',
			'testMultiple2'
		],
	],

];