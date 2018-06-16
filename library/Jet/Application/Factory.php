<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $module_manifest_class_name = __NAMESPACE__.'\Application_Module_Manifest';
	
	/**
	 * @var string
	 */
	protected static $module_manifest_admin_menu_item_class_name = __NAMESPACE__.'\Application_Module_Manifest_AdminMenuItem';

	/**
	 * @var string
	 */
	protected static $module_manifest_admin_dialog_class_name = __NAMESPACE__.'\Application_Module_Manifest_AdminDialog';

	/**
	 * @var string
	 */
	protected static $module_manifest_admin_section_class_name = __NAMESPACE__.'\Application_Module_Manifest_AdminSection';

	/**
	 * @return string|Application_Module_Manifest
	 */
	public static function getModuleManifestClassName()
	{
		return static::$module_manifest_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleManifestClassName( $class_name )
	{
		static::$module_manifest_class_name = $class_name;
	}

	/**
	 * @return Application_Module_Manifest
	 */
	public static function getModuleManifestInstance()
	{
		$class_name = static::getModuleManifestClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getModuleManifestAdminMenuItemClassName()
	{
		return static::$module_manifest_admin_menu_item_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleManifestAdminMenuItemClassName( $class_name )
	{
		static::$module_manifest_admin_menu_item_class_name = $class_name;
	}


	/**
	 * @return Application_Module_Manifest_AdminMenuItem
	 */
	public static function getModuleManifestAdminMenuItemInstance()
	{
		$class_name = static::getModuleManifestAdminMenuItemClassName();

		return new $class_name();
	}


	/**
	 * @return string
	 */
	public static function getModuleManifestAdminDialogClassName()
	{
		return static::$module_manifest_admin_dialog_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleManifestAdminDialogClassName( $class_name )
	{
		static::$module_manifest_admin_dialog_class_name = $class_name;
	}


	/**
	 * @return Application_Module_Manifest_AdminDialog
	 */
	public static function getModuleManifestAdminDialogInstance()
	{
		$class_name = static::getModuleManifestAdminDialogClassName();

		return new $class_name();
	}


	/**
	 * @return string
	 */
	public static function getModuleManifestAdminSectionClassName()
	{
		return static::$module_manifest_admin_section_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleManifestAdminSectionClassName( $class_name )
	{
		static::$module_manifest_admin_section_class_name = $class_name;
	}


	/**
	 * @return Application_Module_Manifest_AdminSection
	 */
	public static function getModuleManifestAdminSectionInstance()
	{
		$class_name = static::getModuleManifestAdminSectionClassName();

		return new $class_name();
	}

	
}