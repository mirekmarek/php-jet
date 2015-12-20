<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return [
	'API_version' => 201401,

	'vendor' => 'Vendor',
	
	'label' => 'Test Module 1',
	'types' => [Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => 'Test module 1...',

	'require' => [],

	'factory_overload_map' => [

	],

	'signals_callbacks' => [
		'/test/ack' => 'testAck',
	]
];