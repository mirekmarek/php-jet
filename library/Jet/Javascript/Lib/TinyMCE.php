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

class Javascript_Lib_TinyMCE extends Javascript_Lib_Abstract {

	/**
	 * @var Javascript_Lib_TinyMCE_Config
	 */
	protected $config;


	/**
	 *
	 */
	public function __construct() {
		$this->config = new Javascript_Lib_TinyMCE_Config();
	}


    /**
     * @param Javascript_Lib_Abstract $lib
     * @return void
     */
    public function adopt( Javascript_Lib_Abstract $lib ) {
        /**
         * @var Javascript_Lib_TinyMCE $lib
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
		$base_URI = dirname($this->config->getURI()).'/';

		$plugins = [];
		$themes = [];
		foreach( $this->config->getEditorConfigs() as $cfg ) {

			if(!in_array($cfg['theme'], $themes)) {
				$themes[] = $cfg['theme'];
			}

			if(isset($cfg['plugins'])) {
				$plugins = array_merge($plugins, explode(' ', $cfg['plugins']));
			}
		}

		$plugins = array_unique($plugins);

		$layout = $this->layout;

		$layout->requireInitialJavascriptCode( JET_TAB.'var Jet_WYSIWYG_editor_configs = '.json_encode($this->config->getEditorConfigs()).';' );
		$layout->requireJavascriptFile( $this->config->getURI() );
		$layout->requireJavascriptFile( $this->config->getWrapperURI() );


		foreach( $themes as $theme ) {
			$layout->requireJavascriptFile( $base_URI.'themes/'.$theme.'/theme.js' );
		}

		foreach( $plugins as $plugin ) {
			$layout->requireJavascriptFile( $base_URI.'plugins/'.$plugin.'/plugin.js' );
		}

		$layout->requireJavascriptCode('tinymce.baseURL='.json_encode( Data_Text::replaceSystemConstants($base_URI) ).';');

		return '';
	}



	/**
	 * Do nothing
	 *
	 * @param string $component - JetJS module
	 * @param array $parameters(optional)
	 *
	 */
	public function requireComponent( $component, $parameters= []) {
	}

	/**
	 * Returns Java Script toolkit version number
	 *
	 * @return string
	 */
	public function getVersionNumber() {
		return $this->config->getVersion();
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