<?php
namespace JetStudio;

use Jet\Translator;

require 'application/init.php';

$current_module = JetStudio::getCurrentModule();

$acm = JetStudio::getModule_AccessControl();
if($current_module instanceof JetStudio_Module_Service_CustomAccessControl) {
	if($current_module->handleAccessControl()!==null) {
		$acm = null;
	}
}

if($acm) {
	Translator::setCurrentDictionary( $acm->getManifest()->getDictionaryName() );
	$acm->handleAccessControl();
}

$current_module?->handle();
