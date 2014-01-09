<?php
 return array(
	"Jet\AdminPages" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\AdminPages',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Web Pages Management',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\AdminRoles" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\AdminRoles',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'ACL Role Management',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\AdminUsers" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\AdminUsers',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Users Management',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\Articles" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\Articles',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Basic acticles module',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\BreadcrumbNavigation" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\BreadcrumbNavigation',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Breadcrumb navigation',
		"description" => 'Displays breadcrumb navigation',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\DefaultAdminUI" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\DefaultAdminUI',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Default admin UI manager',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'admin_UI_manager',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
			"/test/received" => 'testReceived',
			"/test/multiple" => array(
				'testMultiple1',
				'testMultiple2',
			),
		),
		"signals" => array(
			"/test/ack" => 'Reply to test/received signal',
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\DefaultAuth" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\DefaultAuth',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Default authentication and authorization manager',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'auth_manager',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
			"user/login" => 'After user login',
			"user/logout" => 'Before user logout',
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\DefaultSiteUI" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\DefaultSiteUI',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Default site UI manager',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'site_UI_manager',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\Images" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\Images',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Basic images module',
		"description" => '',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\TestModule2" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\TestModule2',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Test Module 2',
		"description" => 'Jet test module ...',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
		),
		"signals" => array(
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
	"Jet\TestModule" => Jet\Application_Modules_Module_Info::__set_state( array(
		"name" => 'Jet\\TestModule',
		"vendor" => 'Jet',
		"version" => '',
		"label" => 'Test Module',
		"description" => 'Jet test module ...',
		"API_version" => 201208,
		"types" => array(
			'general',
		),
		"require" => array(
			'Jet\\TestModule2',
		),
		"factory_overload_map" => array(
		),
		"signals_callbacks" => array(
			"/test/ack" => 'testAck',
		),
		"signals" => array(
			"/test/received" => 'Test signal for DefaultAdminUI',
			"/test/multiple" => 'Test signal for DefaultAdminUI',
		),
		"module_dir" => '',
		"is_installed" => true,
		"is_activated" => true,
		"__signals_signal_object_class_name" => 'Jet\\Application_Signals_Signal',
	) ),
);
;
