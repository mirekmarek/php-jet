<?php
use JetApplication\PageStaticContent;

return [
	[
		'output_position_order' => 1,
		'output' => [PageStaticContent::class, 'get'],
	    'parameters' => [
	    	'text_id' => 'lorem'
	    ]
	]
];