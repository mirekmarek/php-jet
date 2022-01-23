<?php

namespace JetStudio;

use Exception;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$ok = false;
$data = [];
$snippets = [];

$module = Modules::getCurrentModule();

if(
	$module &&
	($new_item = $module->getMenuItems()->catchCreateMenuItemForm())
) {
	Modules::getCurrentModule()->getMenuItems()->addMenuItem( $new_item );

	$form = $module->getMenuItems()->getCreateMenuItemForm();

	$ok = true;
	try {
		$module->getMenuItems()->save();

		UI_messages::success(
			Tr::_( 'Menu item <strong>%item%</strong> has been created', [
				'item' => $new_item->getLabel()
			] )
		);

		$data['id'] = $new_item->getFullId();

	} catch( Exception $e ) {
		$ok = false;

		$form->setCommonMessage( UI_messages::createDanger($e->getMessage()) );
	}

}

$snippets['create_menu_item_form_area'] = Application::getView()->render( 'menu_item/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);