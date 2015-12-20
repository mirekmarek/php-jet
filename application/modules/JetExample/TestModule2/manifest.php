<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Test Module 2',
	
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],

	'description' => 'Jet test module ...',

	'require' => [
		'JetExample.TestModule'
	],

	'factory_overload_map' => []
];