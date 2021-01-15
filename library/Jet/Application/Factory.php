<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class Application_Factory extends BaseObject
{

	/**
	 * @var string
	 */
	protected static string $module_manifest_class_name = Application_Module_Manifest::class;


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

}