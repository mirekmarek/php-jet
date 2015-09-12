<?php
/**
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

class Javascript_Lib_Dojo extends Javascript_Lib_Abstract {
	
	/**
	 * Dojo configuration
	 *
	 * @var Javascript_Lib_Dojo_Config
	 */
	protected $config = null;

	/**
	 * @var string
	 */
	protected $theme = '';

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $packages = ['dojo', 'dijit', 'dojox'];


   
	/**
	 * Dojo config (djConfig)
	 *
	 * @var array
	 */
	protected $djConfig = array(
			'parseOnLoad' => true,
			'locale' => 'en-us',
			'isDebug' => false,
		);

	/**
     *
	 */
	public function __construct() {

		$this->config = new Javascript_Lib_Dojo_Config();

	}

    /**
     * @param Mvc_Layout $layout
     */
    public function setLayout( Mvc_Layout $layout ) {
        parent::setLayout($layout);

        $this->locale = $this->layout->getPage()->getLocale();

        $locale = strtolower($this->locale->getLanguage()).'-'.strtolower($this->locale->getRegion());

        $this->theme = $this->config->getDefaultTheme();

        $this->setOption('parseOnLoad', $this->config->getParseOnLoad());
        $this->setOption('isDebug', $this->config->getIsDebug());
        $this->setOption('locale', $locale);

    }

    /**
     * @param Javascript_Lib_Abstract $lib
     * @return void
     */
    public function adopt( Javascript_Lib_Abstract $lib ) {
        /**
         * @var Javascript_Lib_Dojo $lib
         */

        foreach( $lib->packages as $package ) {
            if(!in_array($package, $this->packages)) {
                $this->packages[] = $package;
            }
        }

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
	 * @param $package
	 */
	public function registerPackage( $package ) {
		$this->packages[] = $package;
	}

	/**
	 * @param string $theme
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
	}

	/**
	 * @return string
	 */
	public function getTheme() {
		return $this->theme;
	}



	/**
	 * Returns dojo version
	 * 
	 * @return string 
	 */
	public function getVersionNumber() {
		return $this->config->getVersion();
	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		return $this->replaceConstants($this->config->getBaseURI());
	}

	/**
	 * Sets option including main setting and djConfig setting
	 *
	 * @param string $option
	 * @param mixed $value
	 *
	 * @throws Javascript_Exception
	 */
	public function setOption( $option, $value ) {

		switch($option){
			case 'parseOnLoad':
			case 'locale':
			case 'isDebug':
				$this->djConfig[$option] = $value;
			break;

			default:
				throw new Javascript_Exception(
					'Unknown Dojo option: \''.$option.'\'',
					Javascript_Exception::CODE_UNKNOWN_JS_LIB_OPTION
				);
			break;
		}
	}
	
	/**
	 * Equivalent to dojo.require().
	 * If $parameters['css'] is set (string or array or strings), additional CSS for given component is written into output
	 *
	 * @param string $component - dojo module
	 * @param array $parameters(optional)
	 */
	public function requireComponent( $component, $parameters=array() ) {
		if( in_array( $component, $this->required_components ) ) {
			return;
		}

		$this->required_components[] = $component;
		if(
			isset($parameters['css']) &&
			$parameters['css']
		){
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
	 * Returns generated HTML code that replaces <jet_layout_javascripts/> in layout
	 *
	 * @throws Javascript_Exception
	 * @return string
	 */
	public function getHTMLSnippet() {

		$this->layout->requireCssFile( $this->replaceConstants( $this->config->getBaseURI().'dojo/resources/dojo.css') );
		$this->layout->requireCssFile( $this->replaceConstants( $this->config->getThemeURI()) );

		$base_URL = $this->replaceConstants( $this->config->getBaseURI() );
		if($this->required_components_CSS){
			foreach($this->required_components_CSS as $css){
				$css = $this->replaceConstants($css);

				$this->layout->requireCssFile( $base_URL.$css );
			}
		}

		$this->layout->requireInitialJavascriptCode( JET_TAB.'var djConfig = '.json_encode($this->djConfig).';' );

		if($this->config->getPackageEnabled()) {

			$package_creator = new Javascript_Lib_Dojo_PackageCreator(
				$this->replaceConstants($this->config->getBaseURI()),
				$this->layout->getPage()->getLocale(),
				$this->packages,
				$this->required_components
			);


			$package_creator->generatePackageFile();

			$package_URI = $package_creator->getPackageURI();

			$this->layout->requireJavascriptFile( $package_URI );
		} else {
			$this->layout->requireJavascriptFile( $this->replaceConstants( $this->config->getDojoJsURI() ) );

			if($this->required_components){
				foreach( $this->required_components as $rc ) {
					if(!$rc) {
						continue;
					}
					$this->layout->requireJavascriptCode(JET_TAB.'dojo.require(\''.$rc.'\');');
				}
			}
		}

		return '';
	}

	/**
	 * Replace constants in values
	 *
	 * @param $value
	 *
	 * @return string
	 */
	protected function replaceConstants($value){

		$replacements = array(
			'VERSION' => $this->config->getVersion(),
			'THEME' => $this->theme
		);

		return Data_Text::replaceData($value, $replacements);
		//return Data_Text::replaceSystemConstants($value, $replacements);
	}


	/**
	 * This method is called when processing is completed and the content is placed in its positions
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	public function finalPostProcess( &$result, Mvc_Layout $layout ) {
		$replace_data = array(
			'DOJO_THEME' => $this->theme
		);

		$result = Data_Text::replaceData($result, $replace_data);
	}
}