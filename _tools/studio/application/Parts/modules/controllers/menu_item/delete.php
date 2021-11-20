<?php

namespace JetStudio;

use Exception;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$module = Modules::getCurrentModule();
$menu_item = Modules::getCurrentMenuItem();

if(
	$module &&
	$menu_item
) {
	$module->getMenuItems()->deleteMenuItem( $menu_item->getSetId(), $menu_item->getMenuId(), $menu_item->getId() );

	$ok = true;
	try {
		$module->getMenuItems()->save();
	} catch( Exception $e) {
		$ok = false;
		UI_messages::danger( $e->getMessage() );
	}

	if($ok) {
		Tr::setCurrentDictionary( 'menus' );

		UI_messages::info( Tr::_( 'Menu item <b>%name%</b> has been deleted', [
			'name' => $menu_item->getId()
		] ) );


		Http_Headers::reload( [], [
			'action',
			'menu_item'
		] );
	}


}
