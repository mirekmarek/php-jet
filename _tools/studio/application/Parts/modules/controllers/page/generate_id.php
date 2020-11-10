<?php
namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$name = Http_Request::GET()->getString('name');
$site_id = Http_Request::GET()->getString('site_id');

$id = Modules_Manifest::generatePageId( $name, $site_id );

AJAX::response(
	[
		'id' => $id
	]
);