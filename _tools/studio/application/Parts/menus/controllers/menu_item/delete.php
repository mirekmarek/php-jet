<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$menu = Menus::getCurrentMenu();
$current = Menus::getCurrentMenuItem();

if(
	$menu &&
	$current
) {
	$menu_item = $menu->deleteMenuItem( $current->getId() );
	$menu->sortItems();


	if( Menus::save() ) {
		UI_messages::info( Tr::_('Menu item <b>%name%</b> has been deleted', [
			'name' => $menu_item->getLabel()
		]) );

		Http_Headers::reload([], ['action', 'item']);
	}

}
