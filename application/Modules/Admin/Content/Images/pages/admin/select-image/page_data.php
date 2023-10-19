<?php
return  [
	'id'                     => 'dialog-select-image',
	'name'                   => 'Dialog - Select image',
	'title'                  => 'Select image',
	'icon'                   => 'images',
	'contents'               => [
		[
			'controller_name'   => 'Dialogs',
			'controller_action' => 'select_image'
		]
	]
];