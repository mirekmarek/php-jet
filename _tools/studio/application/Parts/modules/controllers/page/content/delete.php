<?php

namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();

$POST = Http_Request::POST();

if( $page ) {
	$index = $POST->getInt( 'index' );
	$page->removeContent( $index );

	if( $page->save() ) {
		UI_messages::info( Tr::_( 'Content has been removed...' ) );
	}

	Http_Headers::reload( [], ['action'] );
}
