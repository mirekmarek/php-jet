<?php
namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$related = DataModels::getModel( Http_Request::GET()->getString('related_model') );
$form = DataModels_OuterRelation::getCreateForm( $related );


$ok = false;
$data = [];
$snippets = [];

if( ($new_relation=DataModels_OuterRelation::catchCreateForm( $related, $form )) ) {

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('Relation to <strong>%relation%</strong> has been created',[
					'relation' => $related->getModelName()
				])
			)
		);

		$data = [
			'new_relation_id' => $new_relation->getInternalId()
		];

		$snippets['relations_list_area'] = Application::getView()->render('data_model/model_edit/relations/list');
	}


}


$view = Application::getView();

$view->setVar( 'related', $related );
$view->setVar( 'form', $form);

$snippets['create_relation_form_area'] = $view->render('data_model/create_relation/form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);
