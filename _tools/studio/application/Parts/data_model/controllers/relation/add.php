<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();
$related = DataModels::getClass( Http_Request::POST()->getString( 'related_model_class_name' ) )->getDefinition();
$form = DataModel_Definition_Relation_External::getCreateForm( $related );

$ok = false;
$data = [];
$snippets = [];

if( ($new_relation = DataModel_Definition_Relation_External::catchCreateForm( $related, $form )) ) {
	$current->addExternalRelation( $new_relation );

	if( $current->save() ) {
		UI_messages::success(
			Tr::_( 'Relation to <strong>%relation%</strong> has been created', [
				'relation' => $related->getModelName()
			] )
		);
		$ok = true;
	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}

$view = Application::getView();

$view->setVar( 'related', $related );
$view->setVar( 'form', $form );


$snippets['create_relation_form_area'] = $view->render( 'relation/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);