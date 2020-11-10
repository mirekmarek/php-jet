<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

if(
	$current
) {

	$ok = true;
	try {
		$current->delete();
		DataModels::save();
		
	} catch( Exception $e ) {
		Application::handleError( $e );

		$ok = false;
	}


	if($ok) {

		UI_messages::info( Tr::_('DataModel <strong>%model%</strong> has been deleted', ['model' => $current->getModelName()]) );

		Http_Headers::reload([], ['action','model']);
	}


}

