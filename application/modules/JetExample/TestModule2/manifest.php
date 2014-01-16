<?php
return array(
	'API_version' => 201208,

	'vendor' => 'Jet (example)',
	'label' => 'Test Module 2',
	
	'types' => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_GENERAL),

	'description' => 'Jet test module ...',

	'require' => array(
		'JetExample\\TestModule'
	),

	'factory_overload_map' => array()
);