<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Locale;
use Jet\UI_messages;
use Jet\Tr;

$current = Bases::getCurrentBase();

if(
	$current &&
	($new_ld = $current->catchAddLocaleForm())
) {
	if( $current->save() ) {
		$locale = new Locale( $current->getAddLocaleForm()->getField( 'locale' )->getValue() );

		UI_messages::success( Tr::_( 'Locale <b>%locale%</b> has been added', [
			'locale' => $locale->getName()
		] ) );

		Http_Headers::reload( [], ['action'] );
	}
}
