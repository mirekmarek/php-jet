<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;

$module = Modules::getCurrentModule();
$wizard = Modules_Wizard::getCurrentWizard();
$wizard->init();

if(
	!$module ||
	!$wizard
) {
	die();
}

$view = Application::getView();
$layout = Application::getLayout();
$layout->setScriptName('empty');

Tr::setCurrentNamespace( $wizard->getTrNamespace() );


if( $wizard->catchSetupForm() ) {

	if($wizard->isReady()) {

		$ok = true;
		try {
			$wizard->create();
		} catch( \Exception $e ) {
			$ok = false;

			Application::handleError( $e );
		}

		if($ok) {
			Application::output( $view->render('module_wizard/done') );

			Application::renderLayout();

			die();
		}
	}
}

Application::output( $view->render('module_wizard/setup_form') );


Application::renderLayout();

die();