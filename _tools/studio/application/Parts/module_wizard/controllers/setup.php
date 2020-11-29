<?php
namespace JetStudio;

use Jet\Tr;
use Jet\Exception;


$wizard = ModuleWizard::getCurrentWizard();
$wizard->init();

if( !$wizard ) {
	die();
}

$view = $wizard->getView();

Tr::setCurrentNamespace( $wizard->getTrNamespace() );


if( $wizard->catchSetupForm() ) {

	if($wizard->isReady()) {

		$ok = true;
		try {
			$wizard->create();
		} catch( Exception $e ) {
			$ok = false;

			Application::handleError( $e );
		}

		if($ok) {
			echo $view->render('done');

			die();
		}
	}
}

echo $view->render('setup_form');

die();