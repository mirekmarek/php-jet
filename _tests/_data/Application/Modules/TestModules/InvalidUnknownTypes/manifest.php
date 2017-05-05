<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return [
	'API_version' => 201401,

	'label' => 'Test Module',

	'types' => [ 'UnknownType' ], 'description' => 'Unit test module',

	'require' => [
		'RequireModule1', 'RequireModule2',
	],

];