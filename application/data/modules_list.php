<?php
 return array(
	'JetExample\AdminRoles' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\AdminRoles',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'ACL Role Management',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\AdminUIManager' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\AdminUIManager',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Admin UI manager',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'admin_UI_manager',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
			'/test/received' => 'testReceived',
			'/test/multiple' => array(
				'testMultiple1',
				'testMultiple2',
			),
		),
		'signals' => array(
			'/test/ack' => 'Reply to test/received signal',
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\AdminUsers' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\AdminUsers',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Users Management',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\Articles' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\Articles',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Basic acticles module',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\AuthManager' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\AuthManager',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Authentication and authorization manager',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'auth_manager',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
			'user/login' => 'After user login',
			'user/logout' => 'Before user logout',
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\BreadcrumbNavigation' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\BreadcrumbNavigation',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Breadcrumb navigation',
		'description' => 'Displays breadcrumb navigation',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\Images' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\Images',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Images module',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\SiteUIManager' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\SiteUIManager',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Site UI manager',
		'description' => '',
		'API_version' => 201208,
		'types' => array(
			'site_UI_manager',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\TestModule' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\TestModule',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Test Module',
		'description' => 'Jet test module ...',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
			'/test/ack' => 'testAck',
		),
		'signals' => array(
			'/test/received' => 'Test signal for DefaultAdminUI',
			'/test/multiple' => 'Test signal for DefaultAdminUI',
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
	'JetExample\TestModule2' => Jet\Application_Modules_Module_Info::__set_state( array(
		'name' => 'JetExample\\TestModule2',
		'vendor' => 'Jet (example)',
		'version' => '',
		'label' => 'Test Module 2',
		'description' => 'Jet test module ...',
		'API_version' => 201208,
		'types' => array(
			'general',
		),
		'require' => array(
			'JetExample\\TestModule',
		),
		'factory_overload_map' => array(
		),
		'signals_callbacks' => array(
		),
		'signals' => array(
		),
		'module_dir' => '',
		'is_installed' => true,
		'is_activated' => true,
		'__signals_signal_object_class_name' => 'Jet\\Application_Signals_Signal',
	) ),
);
;
