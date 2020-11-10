<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if(!$current) {
	die();
}

if($current->getIsInstalled()) {
	$current->setIsActive(true);


	if(Modules::save()) {
		if(Modules::setInstalledAndActivatedList()) {
			UI_messages::success( Tr::_('Module <b>%module%</b> has been activated', [
				'module' => $current->getName()
			]) );
		}
	}

}


Http_Headers::reload([], ['action']);
