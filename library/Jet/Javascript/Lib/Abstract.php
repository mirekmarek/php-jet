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
 * @abstract
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

/**
 * Class Javascript_Lib_Abstract
 *
 * @JetFactory:class = 'Jet\Javascript_Factory'
 * @JetFactory:method = 'getJavascriptLibInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Javascript_Lib_Abstract'
 */
abstract class Javascript_Lib_Abstract extends Object {

	/**
	 * The layout instance
	 *
	 * @var Mvc_Layout
	 */
	protected $layout = null;

	/**
	 * List of required components
	 *
	 * @var string[]
	 */
	protected $required_components = array();

	/**
	 * Required CSS files to components.
	 *
	 * @var string[]
	 */
	protected $required_components_CSS = array();

	/**
	 * Some options
	 *
	 * @var mixed[]
	 */
	protected $options = array();

	/**
	 *
	 * @param Mvc_Layout $layout
	 */
	public function __construct( Mvc_Layout $layout ) {
		$this->layout = $layout;
	}

	/**
	 * Returns HTML snippet that initialize Java Script and is included into layout
	 *
	 * @return string
	 */
	abstract public function getHTMLSnippet();


	/**
	 * Returns Java Script toolkit version number
	 *
	 * @return string
	 */
	abstract public function getVersionNumber();

	/**
	 * Require some Java Script component.
	 *
	 * Example:
	 *
	 * $Dojo = $layout->requireJavaScript('Dojo');
	 * $Dojo->requireComponent( 'dijit.form.InputBox' );
	 *
	 * @param string $component
	 * @param mixed[] $parameters (optional)
	 */
	public function requireComponent(
		$component,
		/** @noinspection PhpUnusedParameterInspection */
		$parameters=array()
	) {
		if( in_array( $component, $this->required_components ) ) {
			return;
		}

		$this->required_components[] = $component;
	}

	/**
	 * Sets option value
	 *
	 * @param string $option
	 * @param mixed $value
	 */
	public function setOption( $option, $value ) {
		$this->options[$option] = $value;
	}

	/**
	 * Gets option value
	 *
	 * @param string $option
	 * @param mixed $default_value
	 * @return mixed
	 */
	public function getOption( $option, $default_value ) {
		return isset( $this->options[$option] ) ? $this->options[$option] : $default_value;
	}

	/**
	 * This method is called when processing is completed and the content is placed in its positions
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	abstract public function finalPostProcess( &$result, Mvc_Layout $layout );
}