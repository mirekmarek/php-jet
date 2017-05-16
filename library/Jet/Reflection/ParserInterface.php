<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Reflection_ParserInterface
{

	/**
	 * @param Reflection_ParserData $data
	 */
	public static function parseClassDocComment( Reflection_ParserData $data );

	/**
	 * @param Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( Reflection_ParserData $data );
}