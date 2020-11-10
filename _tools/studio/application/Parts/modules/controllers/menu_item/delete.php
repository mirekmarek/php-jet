<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();

if(
	$current &&
	($namespace_id=$GET->getString('namespace')) &&
	($menu_id=$GET->getString('menu')) &&
	($item_id=$GET->getString('item')) &&
	( $old_item=$current->deleteMenuItem( $namespace_id, $menu_id, $item_id ) )
) {

	if( Modules::save() ) {
		UI_messages::info( Tr::_('Menu item <b>%menu_item%</b> has been deleted', [
			'menu_item' => $old_item->getLabel().' ('.$old_item->getId().')'
		]) );

		Http_Headers::reload([], ['action']);
	}

}
