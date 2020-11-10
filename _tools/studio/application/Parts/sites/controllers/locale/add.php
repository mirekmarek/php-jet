<?php
namespace JetStudio;

use Jet\Exception;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Sites::getCurrentSite();

if(
	$current &&
	($new_ld=Sites::catchAddLocaleForm())
) {
	if( Sites::save() ) {
		UI_messages::success( Tr::_('Locale <b>%locale%</b> has been added', [
			'locale' => $new_ld->getLocale()->getName()
		]) );

		Http_Headers::reload([], ['action']);
	}
}
