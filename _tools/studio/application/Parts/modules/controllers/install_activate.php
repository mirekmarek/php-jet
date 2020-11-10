<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if(!$current) {
	die();
}

$current->setIsInstalled(true);
$current->setIsActive(true);


if(Modules::save()) {
	if(Modules::setInstalledAndActivatedList()) {
		UI_messages::success( Tr::_('Module <b>%module%</b> has been installed and activated', [
			'module' => $current->getName()
		]) );
	}
}


Http_Headers::reload([], ['action']);
