<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Sites::getCurrentSite();

if(
	$current &&
	$current->catchSortLocalesForm()
) {
	if( $current->save() ) {
		UI_messages::success( Tr::_('Saved ...') );
		Http_Headers::reload([], ['action']);
	}

}
