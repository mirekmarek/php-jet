<?php
namespace JetStudio;

use Jet\Exception;
use Jet\Tr;
use Jet\UI_messages;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$ok = false;
$data = [];
$snippets = [];

if(
	$current->catchSortPropertiesForm()
) {

	$form = $current->getSortPropertiesForm();

	if(DataModels::save($form)) {
		$ok = true;

		$current->getSortPropertiesForm()->setCommonMessage(
			UI_messages::createSuccess(Tr::_('Saved ...'))
		);
	}

	$snippets['properties_list_area'] = Application::getView()->render('data_model/model_edit/properties/list');
}


AJAX::formResponse(
	$ok,
	$snippets,
	$data
);