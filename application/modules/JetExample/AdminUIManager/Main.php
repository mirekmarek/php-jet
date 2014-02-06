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
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAdminUI
 */
namespace JetApplicationModule\JetExample\AdminUIManager;
use Jet;

class Main extends Jet\Mvc_UIManagerModule_Abstract {


	/**
	 *
	 * @return Jet\Mvc_Layout
	 */
	public function initializeLayout() {
		if( Jet\Http_Request::GET()->exists('logout') ) {
			Jet\Auth::logout();
		}

		$layout_script = false;

		if($this->router->getServiceType() ==Jet\Mvc_Router::SERVICE_TYPE_STANDARD ) {
			$layout_script = Jet\Mvc::getCurrentPage()->getLayout();
		}

		$layout = new Jet\Mvc_Layout( $this->module_manifest->getModuleDir().'layouts/', $layout_script );
		$layout->setRouter($this->router);

		$public_URI = $this->getPublicURI();
		$JetML_postprocessor = $layout->enableJetML();
		$JetML_postprocessor->setIconsURL( $public_URI.'icons/' );
		$JetML_postprocessor->setFlagsURL( $public_URI.'flags/' );

		return $layout;
	}

	/**
	 * @param Jet\Data_Paginator $paginator
	 *
	 * @return string
	 */
	public function renderPaginator( Jet\Data_Paginator $paginator ) {
		$view = new Jet\Mvc_View( $this->getViewsDir() );
		$view->setVar( 'paginator', $paginator );

		return $view->render('snippets/paginator');
	}

	/**
	 * @param array $columns
	 *
	 * @return string
	 */
	public function renderDataGridHeader( array $columns ) {
		$view = new Jet\Mvc_View( $this->getViewsDir() );
		$view->setVar( 'columns', $columns );

		return $view->render('snippets/data-grid-header');

	}
}