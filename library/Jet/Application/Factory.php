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
class Application_Factory extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $module_manifest_class_name = __NAMESPACE__.'\Application_Module_Manifest';

	/**
	 * @return string|Application_Module_Manifest
	 */
	public static function getModuleManifestClassName()
	{
		return static::$module_manifest_class_name;
	}

	/**
	 * @param string $module_manifest_class_name
	 */
	public static function setModuleManifestClassName( $module_manifest_class_name )
	{
		static::$module_manifest_class_name = $module_manifest_class_name;
	}

	/**
	 * @return Application_Module_Manifest
	 */
	public static function getModuleManifestInstance()
	{
		$class_name = static::getModuleManifestClassName();

		return new $class_name();
	}
}