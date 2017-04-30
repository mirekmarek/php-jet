<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface BaseObject_Reflection_ParserInterface
 * @package Jet
 */
interface BaseObject_Reflection_ParserInterface {

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 */
	public static function parseClassDocComment( BaseObject_Reflection_ParserData $data );

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( BaseObject_Reflection_ParserData $data );
}