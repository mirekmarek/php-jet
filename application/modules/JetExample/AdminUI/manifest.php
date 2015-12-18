<?php
use Jet\Application_Modules_Module_Manifest;

return array(
	'API_version' => 201401,
	'vendor' => 'Jet (example)',

	'label' => 'Admin UI',
	'types' => array( Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL),
	'description' => '',

	'require' => array(),

	'factory_overload_map' => array(),

	'signals_callbacks' => array(
		'/test/received' => 'testReceived',
		'/test/multiple' => array(
			'testMultiple1',
			'testMultiple2'
		),
	),

);