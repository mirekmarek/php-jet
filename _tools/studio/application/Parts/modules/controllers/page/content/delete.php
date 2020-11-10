<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$module = Modules::getCurrentModule();

$POST = Http_Request::POST();

if(
	$module &&
	($page=$module->getPage( $POST->getString('site'), $POST->getString('page') ))
) {
	$index = $POST->getInt('index');
	$page->removeContent( $index );


	if( Modules::save() ) {
		UI_messages::info( Tr::_('Content has been removed...') );

		Http_Headers::reload([], ['action']);
	}

}
