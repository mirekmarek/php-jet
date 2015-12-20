<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Users Management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
		'JetExample.UIElements'
	],

	'signals_callbacks' => [],

];