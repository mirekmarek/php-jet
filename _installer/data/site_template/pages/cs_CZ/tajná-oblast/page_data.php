<?php
return [
	'id'             => 'secret_area', 'is_secret_page' => true, 'name' => 'Tajná oblast', 'title' => 'Tajná oblast',
	'menu_title'     => 'Tajná oblast', 'breadcrumb_title' => 'Tajná oblast', 'layout_script_name' => 'default-secret',
	'headers_suffix' => '', 'body_prefix' => '', 'body_suffix' => '', 'meta_tags' => [
		[
			'attribute' => 'name', 'attribute_value' => 'robots', 'content' => 'noindex',
		],
	], 'contents'    => [
		[
			'output_position' => '', 'output_position_required' => true, 'output_position_order' => 1, 'output' => <<<EOT
<h2>Tajná oblast</h2>
<p>Vítejte v tajné oblasti</p>
EOT,
		],
	],
];

