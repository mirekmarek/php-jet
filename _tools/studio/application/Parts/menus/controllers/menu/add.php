<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_Menu::getCreateForm();

$set = Menus::getCurrentMenuSet();
if( !$set ) {
	die();
}

$ok = false;
$data = [];

if(
($new_menu = Menus_Menu::catchCreateForm())
) {

	$set->appendMenu( $new_menu );

	if( $set->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Menu <strong>%menu%</strong> has been created', [
				'menu' => $new_menu->getLabel()
			] )
		);

		$data = [
			'new_menu_id' => $new_menu->getId()
		];
	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}

}

AJAX::operationResponse(
	$ok,
	[
		$form->getId() . '_form_area' => Application::getView()->render( 'menu/create/form' )
	],
	$data
);