<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$namespace = Menus::getCurrentMenuNamespace();
$current = Menus::getCurrentMenu();

if(
	$namespace &&
	$current
) {
	$menu = $namespace->deleteMenu( $current->getId() );
	$namespace->sortMenus();


	if( Menus::save() ) {
		UI_messages::info( Tr::_('Menu <b>%name%</b> has been deleted', [
			'name' => $menu->getLabel()
		]) );

		Http_Headers::reload([], ['action', 'menu']);
	}

}
