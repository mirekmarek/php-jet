<?php
 return array (
  'JetExample\\AdminRoles' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\AdminRoles',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'ACL Role Management',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\AdminUIManager' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\AdminUIManager',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Admin UI manager',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'admin_UI_manager',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
      '/test/received' => 'testReceived',
      '/test/multiple' => 
      array (
        0 => 'testMultiple1',
        1 => 'testMultiple2',
      ),
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\AdminUsers' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\AdminUsers',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Users Management',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\Articles' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\Articles',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Basic acticles module',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\AuthManager' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\AuthManager',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Authentication and authorization manager',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'auth_manager',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\BreadcrumbNavigation' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\BreadcrumbNavigation',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Breadcrumb navigation',
     'description' => 'Displays breadcrumb navigation',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\Images' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\Images',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Images module',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\SiteUIManager' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\SiteUIManager',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Site UI manager',
     'description' => '',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'site_UI_manager',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\TestModule' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\TestModule',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Test Module',
     'description' => 'Jet test module ...',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
      '/test/ack' => 'testAck',
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
  'JetExample\\TestModule2' => 
  Jet\Application_Modules_Module_Manifest::__set_state(array(
     'name' => 'JetExample\\TestModule2',
     'vendor' => 'Jet (example)',
     'version' => '',
     'label' => 'Test Module 2',
     'description' => 'Jet test module ...',
     'API_version' => 201208,
     'types' => 
    array (
      0 => 'general',
    ),
     'require' => 
    array (
      0 => 'JetExample\\TestModule',
    ),
     'factory_overload_map' => 
    array (
    ),
     'signals_callbacks' => 
    array (
    ),
     'module_dir' => '',
     'is_installed' => true,
     'is_activated' => true,
  )),
);
