<?php
return array(
	'API_version' => 201208,
	'vendor' => 'Jet (example)',

	'label' => 'Authentication and authorization manager',

	'types' => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_AUTH_MANAGER),
	'description' => '',

	'require' => array(),

	'factory_overload_map' => array(),

	'signals' => array(
		'user/login' => 'After user login',
		'user/logout' => 'Before user logout'
	)
);