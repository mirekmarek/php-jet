<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$set = Menus::getCurrentMenuSet();
$current = Menus::getCurrentMenu();

if(
	$set &&
	$current
) {
	$menu = $set->deleteMenu( $current->getId() );


	if( $menu && $set->save() ) {
		UI_messages::info( Tr::_( 'Menu <b>%name%</b> has been deleted', [
			'name' => $menu->getLabel()
		] ) );

		Http_Headers::reload( [], [
			'action',
			'menu'
		] );
	}

}
