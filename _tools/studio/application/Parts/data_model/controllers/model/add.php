<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$POST = Http_Request::POST();

$type = $POST->getString( 'type', '', [
	'Main',
	'1to1',
	'1toN',
	'MtoN'
] );
if( !$type ) {
	die();
}

if( $type != 'Main' ) {
	$class_name = __NAMESPACE__ . '\\DataModel_Definition_Model_Related_' . $type;
} else {
	$class_name = __NAMESPACE__ . '\\DataModel_Definition_Model_Main';
}


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

AJAX::formResponse(
	$ok,
	[
		'create_model_form_area_' . $type => Application::getView()->render( 'model/create/' . $type . '/form' )
	],
	$data
);