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

$menu->deleteMenuItem( $item->getId() );

if( $set->save() ) {
	UI_messages::info( Tr::_( 'Menu item <b>%name%</b> has been deleted', [
		'name' => $item->getLabel()
	] ) );

	Http_Headers::reload( [], [
		'action',
		'item'
	] );
}

