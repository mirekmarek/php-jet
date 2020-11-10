<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_MenuNamespace_Menu_Item::getCreateForm();
$ok = false;
$data = [];

if(
	Menus::getCurrentMenu() &&
	($new_item=Menus_MenuNamespace_Menu_Item::catchCreateForm())
) {

	$new_item->setNamespaceId( Menus::getNamespaces() );
	$new_item->setMenuId( Menus::getCurrentMenuId() );

	Menus::getCurrentMenu()->addMenuItem( $new_item );

	if( Menus::save( $form ) ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Menu item <strong>%item%</strong> has been created',[
				'item' => $new_item->getLabel().' ('.$new_item->getId().')'
			])
		);

		$data = [
			'new_menu_item_id' => $new_item->getId()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_menu_item/form')
	],
	$data
);