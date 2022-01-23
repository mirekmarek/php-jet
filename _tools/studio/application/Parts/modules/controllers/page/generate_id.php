<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$name = Http_Request::GET()->getString( 'name' );
$base_id = Http_Request::GET()->getString( 'base_id' );

$id = Modules_Pages::generatePageId( $name, $base_id );

AJAX::commonResponse(
	[
		'id' => $id
	]
);