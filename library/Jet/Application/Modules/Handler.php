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
abstract class Application_Modules_Handler extends BaseObject
{
	/**
	 *
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function allModulesList();

	/**
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	abstract public function installedModulesList();

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function activatedModulesList();

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleExists( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsInstalled( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsActivated( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function installModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function uninstallModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function activateModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function deactivateModule( $module_name );


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	abstract public function moduleManifest( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	abstract public function moduleInstance( $module_name );

}