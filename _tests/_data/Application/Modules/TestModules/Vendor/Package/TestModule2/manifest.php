<?php
/**
 * @see Jet\Application\Modules_Module_Info
 */
return [
	'API_version' => 201401,

	'label' => 'Test Module 2',

	'vendor' => 'Vendor',

	'types' => [ Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL ],

	'description' => 'Test module 2...',

	'require' => [ 'Vendor.Package.TestModule' ],

];