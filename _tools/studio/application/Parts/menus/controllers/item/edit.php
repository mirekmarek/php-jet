<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$set = Menus::getCurrentMenuSet();
$menu = Menus::getCurrentMenu();
$item = Menus::getCurrentMenuItem();

if( !$set || !$menu || !$item ) {
	die();
}


if( $item->catchEditForm() ) {
	//$menu->sortItems();

	if( $set->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );

		Http_Headers::reload( [], ['action'] );
	}

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
