<?php
namespace JetStudio;

use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$ok = false;
$data = [];
$snippets = [];

if( ($new_property=DataModels_Property::catchCreateForm()) ) {

	$form = DataModels_Property::getCreateForm();
	$form->getField('type')->setDefaultValue( $new_property->getType() );

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('Property <strong>%property%</strong> has been created',[
					'property' => $new_property->getName()
				])
			)
		);

		$snippets['properties_list_area'] = Application::getView()->render('data_model/model_edit/properties/list');
		$snippets['main-toolbar'] = Application::getView()->render('data_model/model_edit/toolbar');
		$snippets['main_parameters'] = Application::getView()->render('data_model/model_edit/main_parameters');

		//$data['id_controller_class'] = DataModels::getCurrentModel()->getIDControllerClassName();
	}


}

$snippets[DataModels_Property::getCreateForm()->getId().'_form_area'] = Application::getView()->render('data_model/create_property/form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);