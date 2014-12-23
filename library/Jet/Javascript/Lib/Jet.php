<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

class Javascript_Lib_Jet extends Javascript_Lib_Abstract {
	
	/**
	 * Framework config
	 *
	 * @var Javascript_Lib_Jet_Config
	 */
	protected $config = null;


	/**
	 * @var string
	 */
	protected $base_URI;

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @param Mvc_Layout $layout
	 *
	 * @throws Javascript_Exception
	 */
	public function __construct( Mvc_Layout $layout ) {
		$this->config = new Javascript_Lib_Jet_Config();

		$this->layout = $layout;

		$this->locale = $this->layout->getRouter()->getLocale();

		$this->layout->requireJavascriptLib('Dojo');

	}
		
	
	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet(){
		$router = $this->layout->getRouter();

		$front_controller = $router->getFrontController();

		$Jet_config = array(
			'base_request_URI' => $this->getBaseRequestURI(),
			'base_URI' => $this->getBaseURI(),
			'modules_URI' => $this->getModulesURI(),
			'service_type_path_fragments_map' => $front_controller->getServiceTypesPathFragmentsMap(),
			'auto_initialize' => true,
			'current_locale' => $router->getLocale(),
			'front_controller_module_name' => $front_controller->getModuleManifest()->getDottedName()
		);
		
		

		$data = $this->_getDataForReplacement();
		if($this->required_components_CSS){
			foreach($this->required_components_CSS as $css){
				$css = Data_Text::replaceData($css, $data);
				$this->layout->requireCssFile( $css );
			}
		}

		$this->layout->requireInitialJavascriptCode(JET_TAB.'var Jet_config = '.json_encode($Jet_config).';');


		if( $this->config->getPackageEnabled()) {
			$package_creator = new Javascript_Lib_Jet_PackageCreator(
				$this->getBasePath(),
				$this->locale,
				$this->required_components
			);


			$package_creator->generatePackageFile();

			$package_URI = $package_creator->getPackageURI();

			$this->layout->requireJavascriptFile( $package_URI );

		} else {
			$this->layout->requireJavascriptFile( $this->getComponentURI('Jet') );


			if($this->required_components){
				foreach( $this->required_components as $rc ) {
					if($rc == 'Jet'){
						continue;
					}
					$this->layout->requireJavascriptFile( $this->getComponentURI($rc) );
				}
			}

		}

		return '';
	}

	/**
	 * @return array
	 */
	protected function _getDataForReplacement(){
		$data = array(
			'JETJS_URI' => $this->getBaseURI(),
		);
		return $data;
	}

	/**
	 * @return string
	 */
	public function getBaseRequestURI() {
		return $this->layout->getRouter()->getPage()->getURI();
	}

	/**
	 * @return string
	 */
	public function getBasePath() {
		return JET_PUBLIC_SCRIPTS_PATH.'Jet/';
	}

	/**
	 * Gets URI to /JetJS/
	 *
	 * In fact: _JetJS_/
	 *
	 *
	 * @return string
	 */
	public function getBaseURI(){
		if(!$this->base_URI) {
			$front_controller = $this->layout->getRouter()->getFrontController();

			$this->base_URI = $front_controller->generateServiceURL( Mvc_Router::SERVICE_TYPE__JETJS_ );
		}

		return $this->base_URI;
	}


	/**
	 * Get URI to modules JS
	 *
	 * In fact _JetJS_/modules/
	 *
	 * @return string
	 */
	public function getModulesURI() {
		return $this->getBaseURI().'modules/';
	}

	/**
	 * Gets proper JS file URI by component and selected framework
	 *
	 * @param string $component
	 * @return string
	 */
	public function getComponentURI( $component ){
		$parts = explode('.', $component);
		return $this->getBaseURI() . implode('/', $parts) . '.js';
	}

	/**
	 *
	 * @param string $component
	 * @return string
	 */
	public function getComponentPath( $component ){
		$parts = explode('.', $component);
		return $this->getBasePath() . implode('/', $parts) . '.js';
	}

	
	/**
	 * Equivalent to Jet.require().
	 * If $parameters['css'] is set (string or array or strings), additional CSS for given component is written into output
	 *
	 * @param string $component - JetJS module
	 * @param array $parameters(optional)
	 */
	public function requireComponent( $component, $parameters=array() ) {
		if( in_array( $component, $this->required_components ) ) {
			return;
		}

		$this->required_components[] = $component;
		if(isset($parameters['css']) && $parameters['css']){
			if(!is_array($parameters['css'])){
				$parameters['css'] = array($parameters['css']);
			} 
			foreach($parameters['css'] as $css){
				if(in_array($css, $this->required_components_CSS)){
					continue;
				}
				$this->required_components_CSS[] = $css;
			}
		}
	}

	/**
	 * Returns Java Script toolkit version number
	 *
	 * @return string
	 */
	public function getVersionNumber() {
		return Version::getVersionNumber();
	}

	/**
	 * This method is called when processing is completed and the content is placed in its positions
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	public function finalPostProcess( &$result, Mvc_Layout $layout ) {
	}

}