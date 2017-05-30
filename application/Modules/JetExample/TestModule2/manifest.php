<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201701,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'  => 'Test Module 2',

	'types' => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ],

	'description' => 'Jet test module ...',

	'require' => [
		'JetExample.TestModule',
	],

];