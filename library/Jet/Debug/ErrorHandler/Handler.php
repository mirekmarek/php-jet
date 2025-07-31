<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/** @phpstan-consistent-constructor */
abstract class Debug_ErrorHandler_Handler
{
	public function __construct()
	{
	
	}
	
	/**
	 * @return static
	 */
	public static function register(): static
	{
		$loader = new static();

		Debug_ErrorHandler::registerHandler( $loader );

		return $loader;
	}


	/**
	 * @return string
	 */
	abstract public function getName(): string;

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	abstract public function handle( Debug_ErrorHandler_Error $error ): void;

	/**
	 *
	 * @return bool
	 */
	abstract public function errorDisplayed(): bool;

}