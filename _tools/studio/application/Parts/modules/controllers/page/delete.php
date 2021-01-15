<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();


if( $page ) {

	$module->deletePage( $page->getSiteId(), $page->getId() );

	if( $module->save() ) {
		UI_messages::info( Tr::_( 'Page <b>%site% : %page%</b> has been deleted', [
			'site' => $page->getSite()->getName(),
			'page' => $page->getName()
		] ) );

		Http_Headers::reload( [], [
			'action',
			'page'
		] );
	}

}
