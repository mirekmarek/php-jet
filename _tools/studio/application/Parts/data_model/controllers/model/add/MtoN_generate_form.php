<?php

namespace JetStudio;

use Jet\AJAX;


$snippets = [
	'create_MtoN_model' => ''
];


if( ($N_class_name = DataModel_Definition_Model_Related_MtoN::catchSelectNForm()) ) {
	$view = Application::getView();
	$view->setVar( 'form', DataModel_Definition_Model_Related_MtoN::getCreateForm( $N_class_name ) );

	$snippets['create_MtoN_model'] = $view->render( 'model/create/MtoN/create_form' );
}


AJAX::formResponse(
	true,
	$snippets
);
