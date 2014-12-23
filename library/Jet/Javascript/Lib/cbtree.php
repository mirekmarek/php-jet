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

		/**
		 * @var Javascript_Lib_Dojo $dojo
		 */
		$dojo = $this->layout->requireJavascriptLib('Dojo');

		$dojo->registerPackage('cbtree');
		$dojo->requireComponent('cbtree.Tree');
		$dojo->requireComponent('cbtree.models.TreeStoreModel');
		$dojo->requireComponent('cbtree.models.ForestStoreModel');
		//$dojo->requireComponent('cbtree.cbtree');

	}


	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet(){
		/**
		 * @var Javascript_Lib_Dojo $dojo
		 */
		$dojo = $this->layout->requireJavascriptLib('Dojo');

		$this->layout->requireCssFile( $dojo->getBaseURI().'cbtree/themes/'.$dojo->getTheme().'/checkbox.css' );


		return '';
	}



	/**
	 * Do nothing
	 *
	 * @param string $component
	 * @param array $parameters(optional)
	 */
	public function requireComponent( $component, $parameters=array() ) {
		/**
		 * @var Javascript_Lib_Dojo $dojo
		 */
		$dojo = $this->layout->requireJavascriptLib('Dojo');

		$dojo->requireComponent($component);
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