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
abstract class Application_Modules_Handler extends BaseObject
{
	/**
	 *
	 *
	 * @return Application_Module_Manifest[]
	 * @throws Application_Modules_Exception
	 *
	 */
	abstract public function allModulesList(): array;

	/**
	 *
	 * @return Application_Module_Manifest[]
	 * @throws Application_Modules_Exception
	 */
	abstract public function installedModulesList(): array;

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function activatedModulesList(): array;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleExists( string $module_name ): bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsInstalled( string $module_name ): bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsActivated( string $module_name ): bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function installModule( string $module_name ): void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function uninstallModule( string $module_name ): void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function activateModule( string $module_name ): void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function deactivateModule( string $module_name ): void;


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	abstract public function moduleManifest( string $module_name ): Application_Module_Manifest;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module
	 * @throws Application_Modules_Exception
	 *
	 */
	abstract public function moduleInstance( string $module_name ): Application_Module;

}