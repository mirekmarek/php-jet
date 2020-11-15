<?php
namespace JetStudio;

use Jet\Http_Request;

$current = DataModels::getCurrentModel();
$related = DataModels::getClass( Http_Request::GET()->getString('related_model') );
$form = DataModel_Definition_Relation_External::getCreateForm( $current );

$view = Application::getView();

$view->setVar( 'related', $related );
$view->setVar( 'form', $form);

echo $view->render('create_relation/form');

Application::end();