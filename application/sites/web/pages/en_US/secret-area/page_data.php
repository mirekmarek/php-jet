<?php
return [
	'id'                 => 'secret_area',
	'is_secret'          => true,
	'name'               => 'Secret area',
	'title'              => 'Secret area',
	'menu_title'         => 'Secret area',
	'breadcrumb_title'   => 'Secret area',
	'layout_script_name' => 'default-secret',
	'meta_tags'          => [
		[
			'attribute'       => 'name',
			'attribute_value' => 'robots',
			'content'         => 'noindex'
		]
	],
	'contents'           => [
		[
			'output_position_order' => 1,
			'output'                => <<<EOT
<h2>Secret area</h2>
<p>Welcome to the secret area</p>
EOT
		],
	]
];

