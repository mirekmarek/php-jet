<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Menus::getCurrentMenuSet();

if(
	$current &&
	$current->catchEditForm()
) {
	if( $current->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );

		Http_Headers::reload( [], ['action'] );
	}

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
