<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Factory_Translator
{
	protected static string $default_backend_class_name = Translator_Backend_Default::class;

	/**
	 * @return string
	 */
	public static function getDefaultBackendClassName(): string
	{
		return self::$default_backend_class_name;
	}

	/**
	 * @param string $default_backend_class_name
	 */
	public static function setDefaultBackendClassName( string $default_backend_class_name ): void
	{
		self::$default_backend_class_name = $default_backend_class_name;
	}

	/**
	 * @return Translator_Backend
	 */
	public static function getDefaultBackendInstance() : Translator_Backend
	{
		$class_name = static::getDefaultBackendClassName();

		return new $class_name();
	}
}