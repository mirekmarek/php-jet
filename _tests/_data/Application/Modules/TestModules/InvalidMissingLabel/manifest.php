<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return array(
	'API_version' => 201401,


	'types' => array( Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL ),
	'description' => 'Unit test module',

	'require' => array(
			'RequireModule1',
			'RequireModule2'
		),

	'factory_overload_map' => array(
		'OldClass1' => 'MyNs\MyClass1',
		'OldClass2' => 'MyNs\MyClass2',
		'OldClass3' => 'MyNs\MyClass3',
	),

	'signals_callbacks' => array(
		'/test/signal1' => 'CallbackMoeduleMethodName1',
		'/test/signal2' => 'CallbackMoeduleMethodName2',
	)

);