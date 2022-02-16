<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
class Debug
{
	/**
	 * @var bool
	 */
	protected static bool $output_is_HTML = false;

	/**
	 * @var bool
	 */
	protected static bool $output_is_XML = false;

	/**
	 * @var bool
	 */
	protected static bool $output_is_JSON = false;

	/**
	 * @param bool $output_is_HTML
	 */
	public static function setOutputIsHTML( bool $output_is_HTML ): void
	{
		static::$output_is_HTML = $output_is_HTML;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getOutputIsHTML(): bool
	{
		return static::$output_is_HTML;
	}


	/**
	 * @param bool $output_is_JSON
	 */
	public static function setOutputIsJSON( bool $output_is_JSON ): void
	{
		static::$output_is_HTML = false;
		static::$output_is_JSON = $output_is_JSON;
	}

	/**
	 * @return bool
	 */
	public static function getOutputIsJSON(): bool
	{
		return static::$output_is_JSON;
	}

	/**
	 * @param bool $output_is_XML
	 */
	public static function setOutputIsXML( bool $output_is_XML ): void
	{
		static::$output_is_XML = $output_is_XML;
	}

	/**
	 * @return bool
	 */
	public static function getOutputIsXML(): bool
	{
		return static::$output_is_XML;
	}


}
