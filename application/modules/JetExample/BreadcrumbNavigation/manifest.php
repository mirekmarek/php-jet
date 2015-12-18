<?php
use Jet\Application_Modules_Module_Manifest;

return array(
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Breadcrumb navigation',
	
	'types' => array( Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL),
	
	'description' => 'Displays breadcrumb navigation',

	'require' => array(),

	'factory_overload_map' => array()
);