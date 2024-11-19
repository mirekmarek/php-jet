<?php
namespace JetStudio;

use Jet\Translator;

require 'application/init.php';

$acm = JetStudio::getModule_AccessControl();
if($acm) {
	Translator::setCurrentDictionary( $acm->getManifest()->getDictionaryName() );
	$acm->handleAccessControl();
}

JetStudio::getCurrentModule()?->handle();


/*


Factory_MVC::setBaseClassName( Bases_Base::class );
*/
