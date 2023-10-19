<?php

return [
	[
		'output_position_order' => 1,
		'output'                => [
			JetApplication\PageStaticContent::class,
			'get'
		],
		'parameters'            => [
			'text_id' => 'lorem'
		]
	]
];