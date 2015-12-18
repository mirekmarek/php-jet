<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;
use Jet\Mvc_Layout;
use Jet\Mvc_Layout_Initializer_Interface;
use Jet\Application_Modules_Module_Abstract;
use Jet\Http_Request;

class Main extends Application_Modules_Module_Abstract implements Mvc_Layout_Initializer_Interface {
    const CONTAINER_ID_GET_PARAMETER = 'container_ID';

	/**
	 *
	 */
	public function initialize() {
	}

    /**
     * @param Mvc_Layout $layout
     */
    public function initializeLayout( Mvc_Layout $layout ) {

        $container_ID = Http_Request::GET()->getString(static::CONTAINER_ID_GET_PARAMETER);

        if(
            $container_ID &&
            preg_match('~^[a-zA-Z0-9_-]+$~', $container_ID)
        ){
            $layout->setUIContainerID( $container_ID );
        }



		$public_URI = $this->module_manifest->getPublicURI();
		$JetML_postprocessor = $layout->enableJetML();
		$JetML_postprocessor->setIconsURL( $public_URI.'icons/' );
		$JetML_postprocessor->setFlagsURL( $public_URI.'flags/' );

	}
}