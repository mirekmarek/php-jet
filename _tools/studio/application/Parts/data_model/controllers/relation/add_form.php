<?php
namespace JetStudio;

use Jet\Http_Request;

$current = DataModels::getCurrentModel();
$related = DataModels::getModel( Http_Request::GET()->getString('related_model') );
$form = DataModels_OuterRelation::getCreateForm( $related );

$view = Application::getView();

$view->setVar( 'related', $related );
$view->setVar( 'form', $form);

echo $view->render('data_model/create_relation/form');

Application::end();