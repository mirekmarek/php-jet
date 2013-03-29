<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAdminUI
 */
namespace JetApplicationModule\Jet\DefaultAdminUI;
use Jet;

class Main extends Jet\Mvc_UIManagerModule_Abstract {


	/**
	 *
	 * @return Jet\Mvc_Layout
	 */
	function initializeLayout() {
		if( Jet\Http_Request::GET()->exists("logout") ) {
			Jet\Auth::logout();
		}

		$layout_script = false;

		if($this->router->getServiceType() ==Jet\Mvc_Router::SERVICE_TYPE_STANDARD ) {
			$layout_script = "default";
		}

		$layout = new Jet\Mvc_Layout( $this->module_info->getModuleDir()."layouts/", $layout_script );
		$layout->setRouter($this->router);

		$public_URI = $this->getPublicURI();
		$JetML_postprocessor = $layout->enableJetML();
		$JetML_postprocessor->setIconsURL( $public_URI."icons/" );
		$JetML_postprocessor->setFlagsURL( $public_URI."flags/" );

		return $layout;
	}
}