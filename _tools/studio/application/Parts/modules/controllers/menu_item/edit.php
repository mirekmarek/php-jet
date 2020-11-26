<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();

$ok = false;
if(
	$current &&
	($namespace_id=$GET->getString('namespace')) &&
	($menu_id=$GET->getString('menu')) &&
	($item_id=$GET->getString('item')) &&
	( $item=$current->getMenuItem( $namespace_id, $menu_id, $item_id ) )
) {

	$form = $item->getEditForm();

	$form->setName( 'menu_item_edit_form_'.$namespace_id.'_'.$menu_id.'_'.$item_id );

	if($item->catchEditForm()) {
		$form = $item->getEditForm();

		if( Modules::save( $form ) ) {
			$ok = true;
			$form->setCommonMessage(
				UI_messages::createSuccess( Tr::_('Saved ...', []) )
			);
		}
	}

	$view = Application::getView();
	$view->setVar('menu_item', $item);

	AJAX::formResponse(
		$ok,
		[
			'menu_item_'.$namespace_id.'_'.$menu_id.'_'.$item_id.'_head' => $view->render('module_edit/menu_items/item-head'),
			'menu_item_'.$namespace_id.'_'.$menu_id.'_'.$item_id.'_form_area' => $view->render('module_edit/menu_items/item-body')
		]
	);

}

