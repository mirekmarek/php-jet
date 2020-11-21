<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_MenuNamespace::getCreateForm();
$ok = false;
$data = [];

if( ($new_namespace=Menus_MenuNamespace::catchCreateForm()) ) {

	Menus::addMenuNamespace($new_namespace);
	if( Menus::save( $form ) ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Menu namespace <strong>%namespace%</strong> has been created',[
				'namespace' => $new_namespace->getName()
			])
		);

		$data = [
			'new_namespace_id' => $new_namespace->getName()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_namespace/form')
	],
	$data
);