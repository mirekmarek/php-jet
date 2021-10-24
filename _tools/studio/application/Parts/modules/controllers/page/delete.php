<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();


if( $page ) {

	if( $page->delete() ) {
		UI_messages::info( Tr::_( 'Page <b>%base% : %page%</b> has been deleted', [
			'base' => $page->getBase()->getName(),
			'page' => $page->getName()
		] ) );
	}

	Http_Headers::reload( [], [
		'action',
		'page'
	] );


}
