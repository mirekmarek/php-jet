<?php
/**
 *
 *
 *
 * Main admin UI module class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_FrontControllerModule
 */
namespace Jet;

abstract class Mvc_FrontControllerModule_Abstract extends Application_Modules_Module_Abstract {
	const CONTAINER_ID_GET_PARAMETER = 'container_ID';
	
	/**
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected $router;

	/**
	 * 
	 * @var Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	protected $breadcrumb_navigation = array();

	/**
	 * @var Data_Tree[]
	 */
	protected $sites_structure = array();

	/**
	 *
	 * @param Mvc_Router_Abstract $router
	 */
	public function setupRouter( Mvc_Router_Abstract $router ) {
		$this->router = $router;
	}

	/**
	 * Setups layout. Example: turn on JetML, setup icons URL and so on.
	 *
	 * @return Mvc_Layout
	 */
	public function initializeLayout() {

		$layout_script = false;
		if($this->router->getServiceType() ==Mvc_Router::SERVICE_TYPE_STANDARD ) {
			$layout_script = $this->router->getPage()->getLayout();
		}

		$layout = new Mvc_Layout( $this->router->getSite()->getLayoutsPath(), $layout_script );
		$layout->setRouter($this->router);


		$public_URI = $this->router->getPublicURI();
		$JetML_postprocessor = $layout->enableJetML();
		$JetML_postprocessor->setIconsURL( $public_URI.'icons/' );
		$JetML_postprocessor->setFlagsURL( $public_URI.'flags/' );

		return $layout;
	}

	/**
	 * @param string $module_name
	 *
	 * @return string
	 */
	public function getLayoutJsReplacementCurrentModule($module_name) {
		$module_manifest = Application_Modules::getModuleManifest($module_name);

		if( ($container_ID=$this->getUIContainerID()) ) {
			return 'Jet.modules.getModuleInstance(\''.$module_manifest->getDottedName().'\', \''.$container_ID.'\').';
		} else {
			return 'Jet.modules.getModuleInstance(\''.$module_manifest->getDottedName().'\').';
		}

	}

	/**
	 *
	 * @return string
	 */
	public function getLayoutJsReplacementFrontController() {
		return 'Jet.getFrontController().';
	}

	/**
	 * @param string $module_name
	 *
	 * @return string
	 */
	public function getLayoutJsReplacementModule($module_name) {
		$module_manifest = Application_Modules::getModuleManifest($module_name);


		return 'Jet.modules.getModuleInstance(\''.$module_manifest->getDottedName().'\').';
	}


	/**
	 * Returns Auth module instance
	 *
	 * @return Auth_ControllerModule_Abstract
	 */
	public function getAuthController() {
		return Application_Modules::getModuleInstance( $this->router->getAuthControllerModuleName() );
	}


	/**
	 * Returns dispatch queue
	 *
	 * @return Mvc_Dispatcher_Queue
	 */
	public function getDispatchQueue() {
		$service_type = $this->router->getServiceType();

		if($service_type==Mvc_Router::SERVICE_TYPE_STANDARD) {
			return $this->router->getPage()->getDispatchQueue();
		}
		else if($service_type==Mvc_Router::SERVICE_TYPE__JETJS_) {
			Translator::setCurrentLocale( $this->router->getLocale() );

			$this->handleJetJS();
			return null;

		}
		else {
			$queue = new Mvc_Dispatcher_Queue();
			$qi = new Mvc_Dispatcher_Queue_Item(
				$this->router->getModuleName(),
				$this->router->getModuleAction(),
				$this->router->getPathFragments()
			);
			$queue->addItem( $qi );

			return $queue;
		}
	}


	/**
	 *
	 * @param Mvc_View $view
	 * @param $script
	 * @param string $position (optional, default:  by current dispatcher queue item, @see Mvc_Layout)
	 * @param bool $position_required (optional, default: by current dispatcher queue item, @see Mvc_Layout)
	 * @param int $position_order (optional, default: by current dispatcher queue item, @see Mvc_Layout)
	 *
	 * @internal param string $output
	 */
	public function renderOutput(
			Mvc_View $view,
			$script,
			$position = null,
			$position_required = null,
			$position_order = null
		) {

		$layout = $this->router->getLayout();
		$view->setLayout($layout);
		$output = $view->render( $script );

		$dispatcher = Mvc_Dispatcher::getCurrentDispatcherInstance();
		$current_queue_item = $dispatcher->getCurrentQueueItem();
		$output_ID = $dispatcher->getCurrentLoopID();

		$module_name = $current_queue_item->getModuleName();
		$content_data = $current_queue_item->getContentData();

		if($content_data) {
			if(!$position) {
				$position = $content_data->getOutputPosition();
			}

			if($position_required===null) {
				$position_required = $content_data->getOutputPositionRequired();
			}

			if($position_order===null) {
				$position_order = $content_data->getOutputPositionOrder();
			}
		}

		if(!$position) {
			$position = Mvc_Layout::DEFAULT_OUTPUT_POSITION;
		}


		$layout->addOutputPart(
				$output,
				$output_ID,
				$module_name,
				$position,
				$position_required,
				$position_order
			);

	}


	/**
	 * The last dispatch step
	 * It should return some output
	 *
	 * @return null|string
	 */
	public function finalizeDispatch() {
		$layout = $this->router->getLayout();
		if ( $layout ) {
			return $layout->render();
		}

		return null;
	}

	/**
	 *
	 * @param DataModel_ID_Abstract|mixed $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param Locale|string|null $locale (optional), default: current locale
	 * @param DataModel_ID_Abstract|mixed $site_ID( optimal), default: current site_ID
	 *
	 * @return string
	 */
	public function generateURI( $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		if(
			$site_ID &&
			$site_ID!=$this->router->getSiteID()
		) {
			return $this->generateURL( $page_ID, $locale, $site_ID );
		}

		;
		if(!($URL = $this->getURLObject( $page_ID, $locale, $site_ID ))) {
			return null;
		}

		return $this->_createURL($URL->getPathPart(), $path_fragments, $GET_params);
	}

	/**
	 *
	 * @param DataModel_ID_Abstract|mixed $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param Locale|string|null $locale (optional), default: current locale
	 * @param DataModel_ID_Abstract|mixed $site_ID( optimal), default: current site_ID
	 *
	 * @return string
	 */
	public function generateURL( $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		if(!($URL = $this->getURLObject( $page_ID, $locale, $site_ID ))) {
			return null;
		}

		return $this->_createURL($URL->toString(), $path_fragments, $GET_params);
	}


	/**
	 *
	 * @param DataModel_ID_Abstract|mixed $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param Locale|string|null $locale (optional), default: current locale
	 * @param DataModel_ID_Abstract|mixed $site_ID( optimal), default: current site_ID
	 *
	 * @return string
	 */
	public function generateNonSchemaURL( $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		if(!($URL = $this->getURLObject( $page_ID, $locale, $site_ID ))) {
			return null;
		}

		return $this->_createURL($URL->getAsNonSchemaURL(), $path_fragments, $GET_params);
	}

	/**
	 * @param $URL
	 * @param $path_fragments
	 * @param $GET_params
	 *
	 * @return string
	 */
	public function _createURL( $URL, array $path_fragments, array $GET_params ) {

		foreach( $path_fragments as $path_fragment ) {
			$URL .= rawurlencode($path_fragment).'/';
		}

		if($GET_params) {
			$URL .= '?'.http_build_query( $GET_params );
		}


		return $URL;
	}


	/**
	 * @param string $page_ID
	 * @param Locale|string|null $locale (optional), default: current locale
	 * @param DataModel_ID_Abstract|mixed $site_ID( optimal), default: current site_ID
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	public function getURLObject( $page_ID, $locale=null, $site_ID=null ) {
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}

		if(!$site_ID) {
			$site_ID = Mvc::getCurrentSiteID();
		}

		/**
		 * @var Mvc_Pages_Page_ID_Abstract $page_ID_i
		 */
		$page_ID_i = Mvc_Factory::getPageInstance()->getID()->createID( $site_ID, $locale, $page_ID );

		return $this->router->getMap()->findMainURL( $page_ID_i );
	}

	/**
	 * @param string $site_ID
	 * @param string|Locale $locale
	 * @return Data_Tree
	 */
	public function getSiteStructure( $site_ID, $locale ) {
		if(!$site_ID) {
			$site_ID = $this->router->getSiteID();;
		}
		if(!$locale) {
			$locale = $this->router->getLocale();
		}

		$ck = $site_ID.':'.$locale;

		if(!isset($this->sites_structure[$ck])) {
			//DO NOT USE PAGE DATA INSTANCE! Why? Because PERFORMANCE!
			$this->sites_structure[$ck] = Mvc_Factory::getPageInstance()->getTree(
				$site_ID,
				$locale,
				false
			);
		}

		return $this->sites_structure[$ck];
	}

	/**
	 * @return Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	public function getBreadcrumbNavigation() {
		if( !$this->breadcrumb_navigation ) {
			$this->breadcrumb_navigation = array();

			$URL_object = $this->router->getMap()->findMainURL( $this->router->getPageID() );

			if(!$URL_object) {
				return null;
			}

			$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
			$navigation_data->setPageID( $URL_object->getPageID() );
			$navigation_data->setTitle( $URL_object->getPageBreadcrumbTitle() );
			$navigation_data->setURI( $URL_object->toString() );

			$this->breadcrumb_navigation[] = $navigation_data;

			while( ($parent_ID = $URL_object->getPageParentID()) ) {

				$URL_object = $this->router->getMap()->findMainURL( $parent_ID );

				if(!$URL_object) {
					break;
				}

				$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
				$navigation_data->setPageID( $URL_object->getPageID() );
				$navigation_data->setTitle( $URL_object->getPageBreadcrumbTitle() );
				$navigation_data->setURI( $URL_object->toString() );

				array_unshift($this->breadcrumb_navigation, $navigation_data);

			}
		}

		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			$bd->setIsLast( $i==$last );
		}

		return $this->breadcrumb_navigation;
	}

	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract $data
	 */
	public function addBreadcrumbNavigationElement( Mvc_NavigationData_Breadcrumb_Abstract $data  ) {
		$this->getBreadcrumbNavigation();

		$this->breadcrumb_navigation[] = $data;
		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			/**
			 * @var Mvc_NavigationData_Breadcrumb_Abstract $bd
			 */
			$bd->setIsLast( $i==$last );
		}

	}

	/**
	 * @param string $title
	 * @param string $URI (optional)
	 * @param Mvc_Pages_Page_ID_Abstract  $page_ID (optional)
	 */
	public function addBreadcrumbNavigationData( $title, $URI='', Mvc_Pages_Page_ID_Abstract $page_ID=null ) {
		$this->getBreadcrumbNavigation();

		$bn = Mvc_Factory::getNavigationDataBreadcrumbInstance();
		$bn->setTitle( $title );
		$bn->setURI( $URI );

		if($page_ID) {
			$bn->setPageID( $page_ID );
		}

		$this->breadcrumb_navigation[] = $bn;

		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			/**
			 * @var Mvc_NavigationData_Breadcrumb_Abstract $bd
			 */
			$bd->setIsLast( $i==$last );
		}

	}

	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract[] $data
	 * @throws Mvc_FrontControllerModule_Exception
	 */
	public function setBreadcrumbNavigation( $data ) {
		$this->breadcrumb_navigation = array();

		foreach( $data as $dat ) {

			if(!$dat instanceof Mvc_NavigationData_Breadcrumb_Abstract) {
				throw new Mvc_FrontControllerModule_Exception(
						'Breadcrumb navigation element must be an instance of  Mvc_NavigationData_Breadcrumb_Abstract ',
						Mvc_FrontControllerModule_Exception::CODE_INVALID_BREADCRUMB_NAVIGATION_DATA_CLASS
					);
			}

			$this->breadcrumb_navigation[] = $dat;
		}
	}


	/**
	 *
	 * @param int $shift_count
	 */
	public function breadcrumbNavigationShift( $shift_count ) {
		$this->getBreadcrumbNavigation();
		if($shift_count<0) {
			$shift_count = count($this->breadcrumb_navigation)+$shift_count;
		}

		for($c=0;$c<$shift_count;$c++) {
			array_shift($this->breadcrumb_navigation);
		}
	}

	/**
	 * @return string|null
	 */
	public function getUIContainerID() {
		$container_ID = Http_Request::GET()->getString(static::CONTAINER_ID_GET_PARAMETER);

		if(
			$container_ID &&
			preg_match('~^[a-zA-Z0-9_-]+$~', $container_ID)
		){
			return $container_ID;
		}

		return null;
	}

	/**
	 * Handles _JetJS_/* requests
	 */
	public function handleJetJS() {
		Javascript::handleJetJSRequest($this->router->getPathFragments());
	}

	/**
	 * Sends 401 HTTP header and shows the access denied page
	 *
	 */
	public function handleAccessDenied() {
		Http_Headers::authorizationRequired();
		if(!Debug_ErrorHandler::displayHTTPErrorPage( Http_Headers::CODE_401_UNAUTHORIZED )) {
			echo 'Unauthorized ...';
		}
		Application::end();
	}

	/**
	 * Sends 404 HTTP header and shows the Page Not Found
	 *
	 */
	public function handle404() {
		Http_Headers::notFound();
		if(!Debug_ErrorHandler::displayHTTPErrorPage( Http_Headers::CODE_404_NOT_FOUND )) {
			echo '404 - Page Not Found';
		}
		Application::end();
	}

	/**
	 * @param bool $do_not_handle_error_page (optional, default:false)
	 *
	 * @return bool
	 */
	public function checkPermissionsToViewThePage( $do_not_handle_error_page=false ) {
		$page = $this->router->getPage();

		if(!$page->getAuthenticationRequired()) {
			return true;
		}

		if($this->router->getAuthenticationRequired()) {
			//some work for ACL ... let's ignore
			return true;
		}

		if(!Auth::getCurrentUserHasPrivilege( Auth::PRIVILEGE_VISIT_PAGE, (string)$page->getID() )) {
			if(!$do_not_handle_error_page) {
				$this->handleAccessDenied();
			}
			return false;
		}

		return true;
	}

	/**
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function getServiceRequestAllowed( $module_name ) {
		$page = $this->router->getPage();

		if($page->getIsAdminUI()) {
			return true;
		}

		$contents = $page->getContents();
		foreach( $contents as $content ) {
			if($content->getModuleName()==$module_name) {
				return true;
			}
		}

		return false;
	}

}