<?php
use Jet\Application_Modules_Module_Manifest;

return array(
	'API_version' => 201401,
	'vendor' => 'Jet (example)',

	'label' => 'Basic acticles module',
	'types' => array( Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL),
	'description' => '',

	'require' => array(
		'JetExample.UIElements'
	),

	'signals_callbacks' => array(),

);