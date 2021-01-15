<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$POST = Http_Request::POST();

$N_class_name = $POST->getString( 'N_class_name' );

if( !$N_class_name ) {
	die();
}


$form = DataModel_Definition_Model_Related_MtoN::getCreateForm( $N_class_name );


$ok = false;
$data = [];

if( ($new_model = DataModel_Definition_Model_Related_MtoN::catchCreateForm( $N_class_name )) ) {
	if( $new_model->create() ) {

		UI_messages::success(
			Tr::_( 'Class <strong>%class%</strong> has been created', [
				'property' => $new_model->getClassName()
			] )
		);

		$ok = true;
		$data['new_class_name'] = $new_model->getClassName();

	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}

$view = Application::getView();
$view->setVar( 'form', $form );

AJAX::formResponse(
	$ok,
	[
		'create_model_form_area_MtoN' => $view->render( 'model/create/MtoN/form' )
	],
	$data
);