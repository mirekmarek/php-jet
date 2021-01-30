<?php

namespace JetStudio;

use Jet\Http_Request;

$related = DataModels::getClass( Http_Request::GET()->getString( 'related_model' ) )->getDefinition();
$form = DataModel_Definition_Relation_External::getCreateForm( $related );

$view = Application::getView();

$view->setVar( 'related', $related );
$view->setVar( 'form', $form );

echo $view->render( 'relation/create/form' );

Application::end();