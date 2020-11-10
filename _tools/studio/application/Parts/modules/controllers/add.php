<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Modules_Manifest::getCreateForm();
$ok = false;
$data = [];

if( ($new_module=Modules_Manifest::catchCreateForm()) ) {

	if( Modules::save( $form ) ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Module <strong>%module%</strong> has been created',[
				'module' => $new_module->getName()
			])
		);

		$data = [
			'new_module_id' => $new_module->getInternalId()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('modules/create_module/form')
	],
	$data
);