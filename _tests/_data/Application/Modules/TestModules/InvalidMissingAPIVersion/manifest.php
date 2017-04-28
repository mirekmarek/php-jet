<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return [

	'label' => 'Test Module',

	'types' => [Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => 'Unit test module',

	'require' => [
			'RequireModule1',
			'RequireModule2'
	],

];