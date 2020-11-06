<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $output_is_HTML = false;

	/**
	 * @var bool
	 */
	protected static $output_is_XML = false;

	/**
	 * @var bool
	 */
	protected static $output_is_JSON = false;

	/**
	 * @param bool $output_is_HTML
	 */
	public static function setOutputIsHTML( $output_is_HTML )
	{
		static::$output_is_HTML = $output_is_HTML;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getOutputIsHTML()
	{
		return static::$output_is_HTML;
	}


	/**
	 * @param bool $output_is_JSON
	 */
	public static function setOutputIsJSON( $output_is_JSON )
	{
		static::$output_is_HTML = false;
		static::$output_is_JSON = $output_is_JSON;
	}

	/**
	 * @return bool
	 */
	public static function getOutputIsJSON()
	{
		return static::$output_is_JSON;
	}

	/**
	 * @param bool $output_is_XML
	 */
	public static function setOutputIsXML( $output_is_XML )
	{
		static::$output_is_XML = $output_is_XML;
	}

	/**
	 * @return bool
	 */
	public static function getOutputIsXML()
	{
		return static::$output_is_XML;
	}


}
