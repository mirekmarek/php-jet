<?php
return array(
	'API_version' => 201208,
	'vendor' => 'Jet (example)',

	'label' => 'Admin UI manager',
	'types' => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_ADMIN_UI_MANAGER),
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