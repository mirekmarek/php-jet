<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$module = Modules::getCurrentModule();
$menu_item = Modules::getCurrentMenuItem();

if(
	$module &&
	$menu_item
) {
	$module->deleteMenuItem( $menu_item->getSetId(), $menu_item->getMenuId(), $menu_item->getId() );

	if( $module->save() ) {
		Tr::setCurrentNamespace('pages');

		UI_messages::info( Tr::_('Menu item <b>%name%</b> has been deleted', [
			'name' => $menu_item->getLabel()
		]) );


		Http_Headers::reload([], ['action', 'menu']);
	}

}
