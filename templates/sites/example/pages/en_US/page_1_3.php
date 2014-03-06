<?php
return array (
	'name' => 'Page 1-3',
	'title' => 'Page 1-3',
	'menu_title' => 'Page 1-3',
	'breadcrumb_title' => 'Page 1-3',
	'URL_fragment' => 'page-1-3',
	'layout' => 'default',
	'headers_suffix' => 'Header suffix',
	'body_prefix' => 'Body prefix',
	'body_suffix' => 'Body suffix',
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