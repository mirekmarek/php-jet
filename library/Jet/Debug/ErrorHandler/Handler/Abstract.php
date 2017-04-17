<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_ErrorHandler
 */
namespace Jet;

abstract class Debug_ErrorHandler_Handler_Abstract {

	/**
	 * @var string
	 */
	protected $HTML_bg_color = '';

	/**
	 * @param array $options
	 */
	public function  __construct( array $options ) {
		$this->setOptions($options);
	}

	/**
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		foreach($options as $k=>$v) {
			if(property_exists($this, $k)) {
				$this->{$k} = $v;
			}
		}
	}

	/**
	 * @abstract
	 *
	 * @param Debug_ErrorHandler_Error $error
	 *
	 */
	abstract public function handle( Debug_ErrorHandler_Error $error );

	/**
	 * @abstract
	 *
	 * Handler must return true if an error displayed
	 *
	 * @return bool
	 */
	abstract public function errorDisplayed();

}