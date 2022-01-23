<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_Menu_Item::getCreateForm();
$ok = false;
$data = [];

$set = Menus::getCurrentMenuSet();
$menu = Menus::getCurrentMenu();

if( !$set || !$menu ) {
	die();
}

if( ($new_item = Menus_Menu_Item::catchCreateForm()) ) {

	$menu->addMenuItem( $new_item );

	if( $set->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Menu item <strong>%item%</strong> has been created', [
				'item' => $new_item->getLabel() . ' (' . $new_item->getId() . ')'
			] )
		);

		$data = [
			'new_menu_item_id' => $new_item->getId()
		];
	} else {
		$form->setCommonMessage( implode( '', UI_messages::get() ) );
	}

}

AJAX::operationResponse(
	$ok,
	[
		$form->getId() . '_form_area' => Application::getView()->render( 'item/create/form' )
	],
	$data
);