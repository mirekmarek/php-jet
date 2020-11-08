<?php
use Jet\PATH;
use Jet\URI;

require_once PATH::LIBRARY().'Jet/URI.php';

$base_URI = '/';

//- It is better to hardcode value on the production system
$request_URI = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
if( ($pos=strpos($request_URI, '?'))!==false ) {
	$request_URI = substr($request_URI, 0, $pos-1);
}
$request_URI = trim( $request_URI, '/' );

if($request_URI) {
	$URI_path_parts = explode( '/', $request_URI );

	while( ($r_path = implode('/', $URI_path_parts )) ) {

		if( file_exists( $_SERVER['DOCUMENT_ROOT'].$r_path.'/application/bootstrap.php' ) ) {
			$base_URI = '/'.$r_path.'/';
			break;
		}

		array_pop($URI_path_parts);
	}
}
//----------------------------------------------------------------

URI::setBASE($base_URI);
URI::setPUBLIC($base_URI.'public/');
