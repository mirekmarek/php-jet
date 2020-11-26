<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$ok = false;
$data = [];
$snippets = [];

if(
	Modules::getCurrentModule() &&
	($new_item=Modules_Manifest::catchCreateMenuItemForm())
) {
	Modules::getCurrentModule()->addMenuItem( $new_item );

	$form = Modules_Manifest::getCreateMenuItemForm();

	if( Modules::save( $form ) ) {
		$ok = true;

		$form->setCommonMessage( UI_messages::createSuccess(
			Tr::_('Menu item <strong>%item%</strong> has been created',[
				'item' => $new_item->getLabel()
			])
		) );

		$snippets['module_menu_items_list_area'] = Application::getView()->render('module_edit/menu_items/list');
	}

}

$form = Modules_Manifest::getCreateMenuItemForm();

$snippets[$form->getId().'_form_area'] = Application::getView()->render('module_edit/add_menu_item_form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);