<?php
namespace JetStudio;

use Jet\Exception;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Sites::getCurrentSite();

if(
	$current &&
	Sites::catchSortLocalesForm()
) {
	if( Sites::save() ) {
		UI_messages::success( Tr::_('Saved ...') );
		Http_Headers::reload([], ['action']);
	}

}
