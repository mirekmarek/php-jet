<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();

if(
	$current &&
	($site_id=$GET->getString('site')) &&
	($page_id=$GET->getString('page')) &&
	( $old_page=$current->deletePage( $site_id, $page_id ) )
) {

	if( Modules::save() ) {
		UI_messages::info( Tr::_('Page <b>%site% : %page%</b> has been deleted', [
			'site' => $old_page->getSite()->getName(),
			'page' => $old_page->getName()
		]) );

		Http_Headers::reload([], ['action']);
	}

}
