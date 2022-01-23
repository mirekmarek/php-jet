<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$name = Http_Request::GET()->getString( 'name' );

$id = Project::generateIdentifier( $name, function( $id ) {
	return false;
} );

AJAX::commonResponse(
	[
		'id' => $id
	]
);