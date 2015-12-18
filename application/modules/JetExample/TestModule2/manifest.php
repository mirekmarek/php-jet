<?php
use Jet\Application_Modules_Module_Manifest;

return array(
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Test Module 2',
	
	'types' => array(Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL),

	'description' => 'Jet test module ...',

	'require' => array(
		'JetExample.TestModule'
	),

	'factory_overload_map' => array()
);