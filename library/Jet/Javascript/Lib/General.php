<?php
/**
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

class Javascript_Lib_General extends Javascript_Lib_Abstract {
	/**
	 * @var array
	 */
	protected $JavaScript_URLs = array();

	/**
	 * @var array
	 */
	protected $JavaScript_code = array();

	/**
	 * @param string $URL
	 */
	public function requireScriptURL( $URL ) {
		$this->JavaScript_URLs[$URL] = $URL;
	}

	/**
	 * @param string $code
	 */
	public function requireScriptCode( $code ) {
		$this->JavaScript_code[] = $code;
	}


	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	public function getHTMLSnippet() {
		$result = '';

		foreach( $this->JavaScript_URLs as $URL ) {
			$result .= '<script type="text/javascript" src="'.$URL.'"></script>'.JET_EOL;

		}

		$result .= '<script type="text/javascript" charset="utf-8">'.JET_EOL;
		foreach( $this->JavaScript_code as $code ) {
			$result .= $code.JET_EOL.JET_EOL;
		}
		$result .= '</script>'.JET_EOL;

		return $result;
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
	public function finalPostProcess(&$result, Mvc_Layout $layout) {
	}
}