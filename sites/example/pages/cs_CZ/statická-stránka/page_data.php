<?php
return [
    'id' => 'static_page',
    'order' => 5,
    'name' => 'Staticka stranka',
    'title' => 'Statická stránka',
	'http_headers' => [
		'testHeader1' => 'test value 1'
	],
    'output' => ['JetApplication\PageStaticContent', 'get']
];
