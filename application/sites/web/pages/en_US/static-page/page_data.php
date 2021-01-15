<?php
return [
	'id'               => 'static_page',
	'name'             => 'Static page',
	'order'            => 3,
	'is_active'        => true,
	'SSL_required'     => false,
	'title'            => 'Static page',
	'menu_title'       => 'Static page',
	'breadcrumb_title' => 'Static page',
	'is_secret'        => false,
	'icon'             => 'file-code',
	'http_headers'     => [
		'testHeader1' => 'test value 1'
	],
	'output'           => '<html>
<head>

</head>
<body>
	<h1>Static page ...</h1>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam aliquam dignissim eros sit amet pharetra. Proin eleifend elit quis ipsum semper imperdiet. Aenean ultrices massa magna. Nam vehicula viverra molestie. Nam auctor mi vel fringilla dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam ac feugiat nulla. Nullam semper ipsum sed libero placerat posuere. Duis rutrum viverra mauris, sit amet sodales lacus auctor a. Nullam sollicitudin odio ipsum, non blandit metus rhoncus id. Praesent vitae enim in purus viverra molestie. Quisque nec tellus et enim blandit sagittis.</p>

	<p>Duis pretium risus vitae dui cursus mollis. Nullam porttitor accumsan eros non interdum. Cras ac sapien et nisl viverra cursus eu quis odio. Fusce condimentum metus pretium, molestie enim eget, sollicitudin elit. Donec volutpat convallis pharetra. Curabitur rutrum ligula nec enim tincidunt, ut commodo quam volutpat. Ut elementum porta magna eu posuere. Morbi tristique maximus laoreet.</p>

	<p>In tristique tortor vitae facilisis mollis. Aliquam sodales purus ante, et viverra lectus posuere eu. Aliquam efficitur nunc eget cursus facilisis. Nunc fringilla felis eu mi volutpat pulvinar. Suspendisse egestas est in euismod convallis. Aliquam lacinia enim pellentesque felis vulputate rhoncus. Etiam eget viverra nisl. Ut ut posuere purus. Nam ex ante, dictum quis accumsan id, bibendum a tellus.</p>

	<p>Praesent dapibus sed tortor eu laoreet. Sed sed nibh mattis, vehicula nisi sed, facilisis odio. In dapibus eget dui eu pharetra. Ut at lacus accumsan, lacinia massa a, pretium lectus. In blandit a odio eget molestie. In fringilla fringilla vehicula. Nam in mauris viverra, sodales turpis vel, imperdiet metus. Duis sagittis eros quis quam elementum, sit amet eleifend ante facilisis.</p>

	<p>Duis at odio vel diam porttitor consectetur. Mauris fermentum auctor tellus, a pharetra elit vehicula a. Interdum et malesuada fames ac ante ipsum primis in faucibus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur dictum purus id libero eleifend, nec accumsan leo sodales. Quisque convallis justo sit amet fermentum commodo. Suspendisse sollicitudin lorem sapien, eget dapibus nisl tincidunt quis. Vivamus non metus eu sapien elementum fermentum et in arcu. Vivamus in ullamcorper justo. Ut egestas id eros ut commodo.</p>
</body>
</html>',
	'meta_tags'        => [
		[
			'attribute'       => 'attribute',
			'attribute_value' => 'example',
			'content'         => 'Example tag',
		],
	],
];
