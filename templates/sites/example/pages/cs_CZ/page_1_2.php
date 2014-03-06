<?php
return array (
	'name' => 'Stránka 1-2',
	'title' => 'Stránka 1-2',
	'menu_title' => 'Stránka 1-2',
	'breadcrumb_title' => 'Stránka 1-2',
	'URL_fragment' => 'stranka-1-2',
	'layout' => 'default',
	'headers_suffix' => 'Kód do hlavičky',
	'body_prefix' => 'Kód na začátek stránky',
	'body_suffix' => 'Kód na konec stránky',
	'meta_tags' => array(
			array(
				'attribute'   => 'Meta1attribute',
				'attribute_value' => 'Meta 1 attribute value',
				'content' => 'Meta 1 content'
			),
			array(
				'attribute'   => 'Meta2attribute',
				'attribute_value' => 'Meta 2 attribute value',
				'content' => 'Meta 2 content'
			),
			array(
				'attribute'   => 'Meta3attribute',
				'attribute_value' => 'Meta 3 attribute value',
				'content' => 'Meta 3 content'
			),
	 ),
	'contents' => array(
				array(
					'module_name' => 'JetExample\TestModule',
					'controller_action' => 'test_action2',
					'output_position' => 'right',
					'output_position_required' => true,
					'output_position_order' => 1
				),
				array(
					'module_name' => 'JetExample\TestModule2',
					'controller_action' => 'test_action1',
					'output_position' => 'top',
					'output_position_required' => true,
					'output_position_order' => 1
				),
				array(
					'module_name' => 'JetExample\TestModule2',
					'controller_action' => 'test_action2',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)
);