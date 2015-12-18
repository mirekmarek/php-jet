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

class Javascript_Lib_cbtree extends Javascript_Lib_Abstract {


	/**
	 *
	 * @param Mvc_Layout $layout
	 *
	 * @throws Javascript_Exception
	 */
	public function __construct( Mvc_Layout $layout ) {
		//$this->config = new Javascript_Lib_cbtree_Config();

		$this->layout = $layout;

        $dojo = new Javascript_Lib_Dojo();

        $dojo->registerPackage('cbtree');
        $dojo->requireComponent('cbtree.Tree');
        $dojo->requireComponent('cbtree.models.TreeStoreModel');
        $dojo->requireComponent('cbtree.models.ForestStoreModel');

        $this->layout->requireJavascriptLib($dojo);

	}


    /**
     * @param Javascript_Lib_Abstract $lib
     * @return void
     */
    public function adopt( Javascript_Lib_Abstract $lib ) {
        /**
         * @var Javascript_Lib_cbtree $lib
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

        $dojo = new Javascript_Lib_Dojo();

		$this->layout->requireCssFile( $dojo->getBaseURI().'cbtree/themes/'.$dojo->getTheme().'/checkbox.css' );


		return '';
	}



	/**
	 * Do nothing
	 *
	 * @param string $component
	 * @param array $parameters(optional)
	 */
	public function requireComponent( $component, $parameters= []) {
        $dojo = new Javascript_Lib_Dojo();

        $dojo->requireComponent( $component );

		$this->layout->requireJavascriptLib($dojo);
	}

	/**
	 * Returns Java Script toolkit version number
	 *
	 * @return string
	 */
	public function getVersionNumber() {
		return 'unknown';
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