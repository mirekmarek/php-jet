<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\DataModel;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$POST = Http_Request::POST();

$type = $POST->getString( key: 'type', valid_values: [
	DataModel::MODEL_TYPE_MAIN,
	DataModel::MODEL_TYPE_RELATED_1TO1,
	DataModel::MODEL_TYPE_RELATED_1TON,
] );
if( !$type ) {
	die();
}

$class_name = __NAMESPACE__ . '\\DataModel_Definition_Model_'.$type;


/**
 * @var DataModel_Definition_Model_Interface $class_name
 */
$form = $class_name::getCreateForm();


$ok = false;
$data = [];

if( ($new_model = $class_name::catchCreateForm()) ) {
	/**
	 * @var DataModel_Definition_Model_Interface $new_model
	 */
	if( $new_model->create() ) {

		UI_messages::success(
			Tr::_( 'Class <strong>%class%</strong> has been created', [
				'class' => $new_model->getClassName()
			] )
		);

		$ok = true;
		$data['new_class_name'] = $new_model->getClassName();

	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}

AJAX::operationResponse(
	$ok,
	[
		'create_model_form_area_' . $type => Application::getView()->render( 'model/create/' . $type . '/form' )
	],
	$data
);