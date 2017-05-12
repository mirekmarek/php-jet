<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Debug_ErrorHandler_Handler_Abstract
 * @package Jet
 */
abstract class Debug_ErrorHandler_Handler
{
	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @param Debug_ErrorHandler_Error $error
	 *
	 */
	abstract public function handle( Debug_ErrorHandler_Error $error );

	/**
	 *
	 * @return bool
	 */
	abstract public function errorDisplayed();

	/**
	 * @return static
	 */
	public static function register()
	{
		$loader = new static();

		Debug_ErrorHandler::registerHandler( $loader );

		return $loader;
	}

}