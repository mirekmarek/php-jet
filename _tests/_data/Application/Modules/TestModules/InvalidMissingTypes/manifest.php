<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return [
	'API_version' => 201401,

	'label' => 'Test Module',

	'description' => 'Unit test module',

	'require' => [
			'RequireModule1',
			'RequireModule2'
	],

	'signals_callbacks' => [
		'/test/signal1' => 'CallbackModuleMethodName1',
		'/test/signal2' => 'CallbackModuleMethodName2',
	]

];