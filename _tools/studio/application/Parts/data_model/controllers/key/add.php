<?php
namespace JetStudio;

use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$ok = false;
$data = [];
$snippets = [];

if( ($new_key=DataModel_Definition_Key::catchCreateForm()) ) {

	$form = DataModel_Definition_Key::getCreateForm();

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('Key <strong>%key%</strong> has been created',[
					'key' => $new_key->getName()
				])
			)
		);
		$snippets['keys_list_area'] = Application::getView()->render('model_edit/keys/list');

	}

}

$snippets[DataModel_Definition_Key::getCreateForm()->getId().'_form_area'] = Application::getView()->render('create_key/form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);