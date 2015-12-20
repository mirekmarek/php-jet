<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'ACL Role Management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
		'JetExample.UIElements'
	],

	'factory_overload_map' => [
	],

	'signals_callbacks' => [],

];