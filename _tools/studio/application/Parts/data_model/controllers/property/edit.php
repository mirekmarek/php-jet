<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use Jet\Exception;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$property = $current->getProperty( Http_Request::GET()->getString('property') );

/**
 * @var DataModels_Property_Interface $property
 */
if(!$property) {
	Application::end();
}

$ok = false;
$data = [];
$snippets = [];


if( ($new_property = $property->catchEditForm()) ) {
	$property = $new_property;

	DataModels::check();
	$current->checkSortOfProperties();

	$current->propertyUpdated( $property );

	$form = $property->getEditForm();

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage( UI_messages::createSuccess(Tr::_('Saved ...')) );

		$data['head_css_class'] = $property->getHeadCssClass();
		$data['id_controller_class'] = $current->getIDControllerClassName();

		$snippets['main-toolbar'] = Application::getView()->render('data_model/model_edit/toolbar');
		$snippets['main_parameters'] = Application::getView()->render('data_model/model_edit/main_parameters');
	}

}



$view = Application::getView();
$view->setVar('property', $property);

$snippets['property_detail_area_'.$property->getInternalId()] = $view->render('data_model/model_edit/properties/list/item-body');
$snippets['property_header_area_'.$property->getInternalId()] = $view->render('data_model/model_edit/properties/list/item-header');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);