<?php
return [
    'id' => 'static_page',
    'order' => 5,
    'name' => 'Static page',
    'title' => 'Static page',
	'http_headers' => [
		'testHeader1' => 'test value 1'
	],
    'output' => ['JetApplication\PageStaticContent', 'get']
];
