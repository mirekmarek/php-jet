<?php
return array(
	'API_version' => 201208,

	'vendor' => 'Jet (example)',
	'label' => 'Test Module',
	'types' => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_GENERAL),
	'description' => 'Jet test module ...',

	'require' => array(  ),

	'factory_overload_map' => array(

	),

	'signals_callbacks' => array(
		'/test/ack' => 'testAck',
	),
	
	'signals' => array(
		'/test/received' => 'Test signal for DefaultAdminUI',
		'/test/multiple' => 'Test signal for DefaultAdminUI'
	)
    
);