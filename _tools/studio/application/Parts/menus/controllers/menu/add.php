<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_MenuNamespace_Menu::getCreateForm();
$ok = false;
$data = [];

if(
	Menus::getCurrentMenuNamespace() &&
	($new_menu=Menus_MenuNamespace_Menu::catchCreateForm())
) {

	$new_menu->setNamespaceName( Menus::getCurrentMenuNamespaceName() );

	Menus::getCurrentMenuNamespace()->addMenu( $new_menu );

	if( Menus::save( $form ) ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Menu <strong>%menu%</strong> has been created',[
				'menu' => $new_menu->getLabel()
			])
		);

		$data = [
			'new_menu_id' => $new_menu->getId()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_menu/form')
	],
	$data
);