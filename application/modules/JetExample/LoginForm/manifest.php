<?php
use Jet\Application_Modules_Module_Manifest;
use JetExampleApp\Mvc_Page;

return [
	'API_version' => 201401,
	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'Login page',

	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_SYSTEM],
	'description' => '',

	'is_mandatory' => true,
	'require' => [],

	'admin_sections' => [
		Mvc_Page::CHANGE_PASSWORD_ID => [
			'is_system_page' => true,
			'title' => 'Change password',
			'URL_fragment' => 'change-password',
			'action' => 'change_password'
		]
	]

];