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
class Factory_Application
{

	/**
	 * @var string
	 */
	protected static string $module_manifest_class_name = Application_Module_Manifest::class;

	/**
	 * @var string
	 */
	protected static string $default_handler_class_name = Application_Modules_Handler_Default::class;

	/**
	 * @return string
	 */
	public static function getModuleManifestClassName(): string
	{
		return static::$module_manifest_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleManifestClassName( string $class_name ): void
	{
		static::$module_manifest_class_name = $class_name;
	}

	/**
	 * @return Application_Module_Manifest
	 */
	public static function getModuleManifestInstance(): Application_Module_Manifest
	{
		$class_name = static::getModuleManifestClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getDefaultModuleHandlerClassName(): string
	{
		return self::$default_handler_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setDefaultModuleHandlerClassName( string $class_name ): void
	{
		self::$default_handler_class_name = $class_name;
	}

	/**
	 * @return Application_Modules_Handler
	 */
	public static function getDefaultModuleHandlerInstance(): Application_Modules_Handler
	{
		$class_name = static::getDefaultModuleHandlerClassName();

		return new $class_name();
	}

}