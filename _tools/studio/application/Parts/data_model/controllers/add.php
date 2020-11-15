<?php
namespace JetStudio;

use Jet\AJAX;

$form = DataModels::getCreateForm();
$ok = false;
$data = [];

if( ($new_model=DataModel_Definition_Model_Main::catchCreateForm()) ) {

	if(DataModels::save($form)) {
		$ok = true;
		$data = [
			'new_model_id' => $new_model->getClassName()
		];

	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_data_model/form')
	],
	$data
);