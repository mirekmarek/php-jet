<?php
return [
	'vendor'      => '%<AUTHOR>%',
	'label'       => '%<LABEL>%',
	'description' => '%<DESCRIPTION>%',
	
	'ACL_actions' => [
		'get_%<ACL_ENTITY_NAME>%'    => 'Get %<ACL_ENTITY_NAME>% data',
		'add_%<ACL_ENTITY_NAME>%'    => 'Add new %<ACL_ENTITY_NAME>%',
		'update_%<ACL_ENTITY_NAME>%' => 'Update %<ACL_ENTITY_NAME>%',
		'delete_%<ACL_ENTITY_NAME>%' => 'Delete %<ACL_ENTITY_NAME>%',
	],
	

	'pages' => [
		'%<PAGE_BASE_ID>%' => [
			'%<PAGE_ID>%' => [
				'title'                  => '%<PAGE_TITLE>%',
				'icon'                   => '%<PAGE_ICON>%',
				'relative_path_fragment' => '%<PAGE_PATH_FRAGMENT>%',
				'contents' => [
					[
						'controller_action' => 'default'
					]
				],
			],
		],
	],

	'menu_items' => [
		'%<TARGET_MENU_SET_ID>%' => [
			'%<TARGET_MENU_ID>%' => [
				'%<MENU_ITEM_ID>%' => [
					'separator_before' => true,
					'page_id'          => '%<PAGE_ID>%',
					'index'            => 200,
				],
			],
		],
	],

];