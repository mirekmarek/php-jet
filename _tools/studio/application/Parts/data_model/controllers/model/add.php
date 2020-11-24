<?php
namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$POST = Http_Request::POST();

$type = $POST->getString('type', '', ['Main', '1to1', '1toN', 'MtoN']);
if(!$type) {
	die();
}

if($type!='Main') {
	$type = 'Related_'.$type;
}

$class_name = __NAMESPACE__.'\\DataModel_Definition_Model_'.$type;

/**
 * @var DataModel_Definition_Model_Interface $class_name
 */
$form = $class_name::getCreateForm();


$ok = false;
$data = [];

if( ($new_model=$class_name::catchCreateForm()) ) {

	if($new_model->create()) {

		UI_messages::success(
			Tr::_('Class <strong>%property%</strong> has been created',[
				'property' => $new_model->getClassName()
			])
		);

		$ok = true;
		$data['new_class_name'] = $new_model->getClassName();

	} else {
		$message = implode('', UI_messages::get());

		$form->setCommonMessage( $message );
	}
}

AJAX::formResponse(
	$ok,
	[
		'create_model_form_area' => Application::getView()->render('model/create/'.$type.'/form')
	],
	$data
);