<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$ok = false;
$data = [];
$snippets = [];

$module = Modules::getCurrentModule();

if(
	$module &&
	($new_item = $module->catchCreateMenuItemForm())
) {
	Modules::getCurrentModule()->addMenuItem( $new_item );

	$form = $module->getCreateMenuItemForm();

	if( $module->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Menu item <strong>%item%</strong> has been created', [
				'item' => $new_item->getLabel()
			] )
		);

		$data['id'] = $new_item->getFullId();

	} else {
		$form->setCommonMessage( implode( '', UI_messages::get() ) );
	}

}

$snippets['create_menu_item_form_area'] = Application::getView()->render( 'menu_item/create/form' );

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);