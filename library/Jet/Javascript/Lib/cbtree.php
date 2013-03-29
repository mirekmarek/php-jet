<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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

		$dojo = $this->layout->requireJavascriptLib("Dojo");
		$dojo->requireComponent("dijit.Tree");
		$dojo->requireComponent("dijit.form.CheckBox");
		$dojo->requireComponent("dijit.tree.ForestStoreModel");
		$dojo->requireComponent("dojo.data.ItemFileWriteStore");

	}


	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet(){

		$result = "";

		$result .= '<link rel="stylesheet" type="text/css" href="'.JET_PUBLIC_SCRIPTS_URI.'cbtree/themes/%DOJO_THEME%/Checkbox.css ">'."\n";
		$result .= '<script type="text/javascript" charset="utf-8">' . "\n";
		$result .= "dojo.registerModulePath(\"cbtree\",\"".JET_PUBLIC_SCRIPTS_URI."cbtree\");\n";
		$result .= "dojo.require(\"cbtree.CheckBoxTree\");\n";
		$result .= "</script>\n";

		return $result;
	}



	/**
	 * Do nothing
	 *
	 * @param string $component
	 * @param array $parameters(optional)
	 * @return void
	 */
	public function requireComponent( $component, $parameters=array() ) {
	}

	/**
	 * Returns Java Script toolkit version number
	 *
	 * @return string
	 */
	public function getVersionNumber() {
		return "unknown";
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