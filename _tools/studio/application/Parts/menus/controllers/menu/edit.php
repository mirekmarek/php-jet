<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$set = Menus::getCurrentMenuSet();

if( $set ) {
	$current = Menus::getCurrentMenu();

	if(
		$current &&
		$current->catchEditForm()
	) {
		$set->sortMenus();

		if( $set->save() ) {
			UI_messages::success( Tr::_( 'Saved ...' ) );

			Http_Headers::reload( [], ['action'] );
		}

	} else {
		UI_messages::danger(
			Tr::_( 'There are some problems ... Please check the form.' )
		);
	}

}
