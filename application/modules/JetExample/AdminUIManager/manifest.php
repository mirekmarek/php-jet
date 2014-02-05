<?php
return array(
	'API_version' => 201401,
	'vendor' => 'Jet (example)',

	'label' => 'Admin UI manager',
	'types' => array(Jet\Application_Modules_Module_Manifest::MODULE_TYPE_ADMIN_UI_MANAGER),
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