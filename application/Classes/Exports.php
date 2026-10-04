<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Service_List;
use Jet\SysConf_Path;
use Jet\Tr;

class Exports
{

	protected static string $module_name_prefix = 'Exports.';

	protected static ?string $root_path = null;
	
	
	public static function getManager() : ?Application_Service_General_ExportsManager
	{
		/** @phpstan-ignore return.type */
		return Application_Service_General::Exports();
	}
	

	public static function getModuleNamePrefix(): string
	{
		return self::$module_name_prefix;
	}

	public static function setModuleNamePrefix( string $module_name_prefix ): void
	{
		self::$module_name_prefix = $module_name_prefix;
	}

	/**
	 * @return string|null
	 */
	public static function getRootPath(): ?string
	{
		if(!static::$root_path) {
			static::$root_path = SysConf_Path::getBase().'exports/';
		}

		return static::$root_path;
	}

	/**
	 * @param string|null $root_path
	 */
	public static function setRootPath( ?string $root_path ): void
	{
		static::$root_path = $root_path;
	}
	
	/**
	 * @return Exports_Definition[]
	 */
	public static function getExportsList() : array
	{
		$list = [];
		foreach( Application_Service_List::findPossibleModules(Exports_Provider_Interface::class) as $module) {
			/**
			 * @var Exports_Provider_Interface&Application_Module $module
			 */
			Tr::setCurrentDictionaryTemporary(
				dictionary: $module->getModuleManifest()->getName(),
				action: function() use (&$list, $module) {
					foreach( $module->getExportsDefinitions() as $sys_service ) {
						$list[ $sys_service->getCode() ] = $sys_service;
					}
				}
			);
		}

		return $list;
	}
	
	public static function getExport( string $code ) : ?Exports_Definition
	{
		$list = static::getExportsList();
		
		return $list[$code]??null;
	}
}