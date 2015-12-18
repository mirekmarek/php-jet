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
    protected $components_base_URL = '';

    /**
     * @var string
     */
    protected $REST_base_URL = '';

    /**
     * @var string
     */
    protected $AJAX_base_URL = '';

    /**
     * @var string
     */
    protected $UI_module_name = '';

	/**
	 *
	 *
	 * @throws Javascript_Exception
	 */
	public function __construct() {
		$this->config = new Javascript_Lib_Jet_Config();
	}

    /**
     * @param Mvc_Layout $layout
     */
    public function setLayout( Mvc_Layout $layout ) {
        parent::setLayout($layout);

        $dojo = new Javascript_Lib_Dojo();
        $layout->requireJavascriptLib($dojo);
    }


    /**
     * @param Javascript_Lib_Abstract $lib
     * @return void
     */
    public function adopt( Javascript_Lib_Abstract $lib ) {
        /**
         * @var Javascript_Lib_Jet $lib
         */


        foreach( $lib->required_components as $component ) {
            if(!in_array($component, $this->required_components)) {
                $this->required_components[] = $component;
            }
        }

        foreach( $lib->required_components_CSS as $CSS ) {
            if(!in_array($CSS, $this->required_components_CSS)) {
                $this->required_components_CSS[] = $CSS;
            }
        }

        foreach( $this->options as $key=>$val ) {
            if(!array_key_exists($key, $this->options)) {
                $this->options[$key] = $val;
            }
        }
    }

	
	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet(){

		$Jet_config = [
			'components_base_URL' => $this->getComponentsBaseURL(),
			'REST_base_URL' => $this->getRESTBaseURL(),
			'AJAX_base_URL' => $this->getAJAXBaseURL(),
			'auto_initialize' => true,
			'current_locale' => $this->layout->getPage()->getLocale(),
			'UI_module_name' => $this->getUIModuleName()
		];
		
		

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
				$this->layout->getPage()->getLocale(),
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
     * @param string $components_base_URL
     */
    public function setComponentsBaseURL($components_base_URL)
    {
        $this->components_base_URL = $components_base_URL;
    }

    /**
     * @return string
     */
    public function getComponentsBaseURL()
    {
        return $this->components_base_URL;
    }

    /**
     * @param string $AJAX_base_URL
     */
    public function setAJAXBaseURL($AJAX_base_URL)
    {
        $this->AJAX_base_URL = $AJAX_base_URL;
    }

    /**
     * @return string
     */
    public function getAJAXBaseURL()
    {
        return $this->AJAX_base_URL;
    }

    /**
     * @param string $REST_base_URL
     */
    public function setRESTBaseURL($REST_base_URL)
    {
        $this->REST_base_URL = $REST_base_URL;
    }

    /**
     * @return string
     */
    public function getRESTBaseURL()
    {
        return $this->REST_base_URL;
    }

    /**
     * @param string $UI_module_name
     */
    public function setUIModuleName($UI_module_name)
    {
        $this->UI_module_name = $UI_module_name;
    }

    /**
     * @return string
     */
    public function getUIModuleName()
    {
        return $this->UI_module_name;
    }



	/**
	 * @return array
	 */
	protected function _getDataForReplacement(){
		$data = [
			'JETJS_URI' => $this->getComponentsBaseURL(),
		];
		return $data;
	}


	/**
	 * @return string
	 */
	public function getBasePath() {
		return JET_PUBLIC_SCRIPTS_PATH.'Jet/';
	}


	/**
	 * Gets proper JS file URI by component and selected framework
	 *
	 * @param string $component
	 * @return string
	 */
	public function getComponentURI( $component ){
		$parts = explode('.', $component);
		return $this->getComponentsBaseURL() . implode('/', $parts) . '.js';
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
	public function requireComponent( $component, $parameters= []) {
		if( in_array( $component, $this->required_components ) ) {
			return;
		}

		$this->required_components[] = $component;
		if(isset($parameters['css']) && $parameters['css']){
			if(!is_array($parameters['css'])){
				$parameters['css'] = [$parameters['css']];
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