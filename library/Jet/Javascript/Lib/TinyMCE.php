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

class Javascript_Lib_TinyMCE extends Javascript_Lib_Abstract {

	/**
	 * @var Javascript_Lib_TinyMCE_Config
	 */
	protected $config;


	/**
	 *
	 * @param Mvc_Layout $layout
	 *
	 * @throws Javascript_Exception
	 */
	public function __construct( Mvc_Layout $layout ) {
		$this->config = new Javascript_Lib_TinyMCE_Config();

		$this->layout = $layout;

	}


	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet(){
		$result = "";


		$result .= '<script type="text/javascript">'."\n";
		$result .= '  var Jet_WYSIWYG_editor_configs = '.json_encode($this->config->getEditorConfigs()).';'."\n";
		$result .= '</script>'."\n";

		$result .= '<script type="text/javascript" src="'.$this->config->getURI().'" charset="utf-8"></script>'."\n";
		$result .= '<script type="text/javascript" src="'.$this->config->getWrapperURI().'" charset="utf-8"></script>'."\n";

		return $result;
	}



	/**
	 * Do nothing
	 *
	 * @param string $component - JetJS module
	 * @param array $parameters(optional)
	 *
	 */
	public function requireComponent( $component, $parameters=array() ) {
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