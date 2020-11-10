<?php
namespace JetStudio;

use Jet\AJAX;

$form = DataModels_Model::getCreateForm();
$ok = false;
$data = [];

if( ($new_model=DataModels_Model::catchCreateForm()) ) {

	if(DataModels::save($form)) {
		$ok = true;
		$data = [
			'new_model_id' => $new_model->getInternalId()
		];

	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('data_model/create_data_model/form')
	],
	$data
);