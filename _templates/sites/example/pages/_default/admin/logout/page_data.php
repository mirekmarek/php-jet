<?php
namespace Jet;

$UI_module = Application_Modules::getModuleInstance('JetExample.AdminUI');


return [
    'ID' => 'admin/logout',
    'name' => 'Admin logout',
    'title' => 'Administrace - logout',
    'menu_title' => 'Administrace - odhlášení',
    'breadcrumb_title' => 'Odhlášení',
    'custom_layouts_path' => $UI_module->getLayoutsDir(),
    'layout_script_name' => 'default',
    'is_admin_UI' => true,
    'meta_tags' => [],
    'contents' => [
        [
            'is_dynamic' => true,
            'module_name' => 'JetExample.AdminUI',
            'controller_action' => 'logout',
            'output_position' => '',
            'output_position_required' => true,
            'output_position_order' => 0
        ]
    ]
];