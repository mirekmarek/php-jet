<?php
use Jet\Application_Modules_Module_Manifest;
use JetShop\Admin\Custom\Page;

return [
	'API_version' => 201401,
	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'JetShop Admin - Authentication and Authorization Controller',

	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_AUTH_CONTROLLER],
	'description' => '',

	'require' => [],

	'sections' => [
		Page::CHANGE_PASSWORD_ID => [
			'is_system_page' => true,
			'title' => 'Change password',
			'URL_fragment' => 'change-password',
			'action' => 'change_password'
		]
	]

];