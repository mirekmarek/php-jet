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
abstract class Application_Services extends BaseObject
{
	protected static ?array $map = null;
	/**
	 * @var Application_Service_MetaInfo[]|null
	 */
	protected static ?array $services_meta_info = null;
	
	protected static ?array $config = null;
	
	protected static array $services = [];
	
	abstract public static function getCfgFilePath() : string;
	
	protected static function registerServices(): void
	{
		static::_registerServices( static::GROUP );
	}
	
	
	public static function loadCfg() : void
	{
		if(static::$config===null) {
			$path = static::getCfgFilePath();
			
			if(!IO_File::exists($path)) {
				static::$config = [];
				static::saveCfg();
			}
			
			static::$config = require $path;
		}
	}
	
	public static function saveCfg() : void
	{
		static::loadCfg();
		
		IO_File::writeDataAsPhp(
			static::getCfgFilePath(),
			static::$config
		);
	}
	
	public static function getConfig() : array
	{
		static::loadCfg();
		
		return static::$config;
	}
	
	public static function setServiceConfig( string $interface_class_name, string $module_class_name ) : void
	{
		static::loadCfg();
		
		static::$config[$interface_class_name] = $module_class_name;
	}
	
	/**
	 * @return Application_Service_MetaInfo[]
	 */
	public static function getRegisteredServices() : array
	{
		if(static::$services_meta_info===null) {
			static::registerServices();
		}
		
		
		return static::$services_meta_info;
	}
	
	public static function getServiceMetaInfo( string $interface_class_name ) : ?Application_Service_MetaInfo
	{
		if(static::$services_meta_info===null) {
			static::registerServices();
		}
		
		if(!isset( static::$services_meta_info[$interface_class_name ])) {
			throw new Exception('Unknown service '.$interface_class_name.'');
		}
		
		return static::$services_meta_info[$interface_class_name ];
	}
	
	protected static function _registerService( Application_Service_MetaInfo $service_meta_info ) : void
	{
		static::$services_meta_info[$service_meta_info->getInterfaceClassName()] = $service_meta_info;
		
	}
	
	protected static function _registerServices( string $group ) : void
	{
		$definitions = Application_Service_MetaInfo::getServices( $group );
		
		static::$services_meta_info = [];
		
		foreach($definitions as $service_meta_info) {
			static::_registerService( $service_meta_info );
		}
		
		uasort( static::$services_meta_info, function( Application_Service_MetaInfo $a, Application_Service_MetaInfo $b ) {
			return strcmp( $a->getName(), $b->getName() );
		} );
	}
	
	
	/**
	 * @return Application_Service_MetaInfo[]
	 */
	public static function getServicesMetaInfo() : array
	{
		if(static::$services_meta_info===null) {
			static::$services_meta_info=[];
			static::registerServices();
		}
		
		return static::$services_meta_info;
	}
	
	
	protected static function initMap() : void
	{
		
		if(static::$map!==null) {
			return;
		}
		
		static::$map = [];
		
		$modules = Application_Modules::activatedModulesList();
		
		foreach( $modules as $module_name => $module_info ) {
			$module = Application_Modules::moduleInstance( $module_name );
			
			$implements = class_implements( $module , false);
			
			foreach($implements as $ifc) {
				if(!isset(static::$map[$ifc])) {
					static::$map[$ifc] = [];
				}
				
				static::$map[$ifc][$module_name] = $module;
			}
			
			$parents = class_parents( $module, false );
			foreach($parents as $parent) {
				if(!isset(static::$map[$parent])) {
					static::$map[$parent] = [];
				}
				
				static::$map[$parent][$module_name] = $module;
			}
			
		}
		
	}
	
	/**
	 * @param string $service_interface
	 * @param string|null $module_name_prefix
	 *
	 * @return Application_Module[]
	 */
	public static function findServices( string $service_interface, ?string $module_name_prefix=null ) : array
	{
		static::initMap();
		if(!isset( static::$map[$service_interface])) {
			return [];
		}
		
		if(!$module_name_prefix) {
			return static::$map[$service_interface];
		}
		
		$services = [];
		
		foreach( static::$map[$service_interface] as $name=> $module ) {
			if(str_starts_with($name, $module_name_prefix)) {
				$services[$name] = $module;
			}
		}
		
		return $services;
	}
	
	
	public static function get( string $service_interface ) : ?Application_Module
	{
		if(isset( static::$services[$service_interface])) {
			return static::$services[$service_interface];
		}
		
		static::loadCfg();
		if(!array_key_exists($service_interface, static::$config)) {
			
			$meta_info = static::getServiceMetaInfo( $service_interface );
			
			foreach( static::findServices( $service_interface, $meta_info->getModuleNamePrefix() ) as $service) {
				
				static::$config[$service_interface] = $service->getModuleManifest()->getName();
				static::$services[$service_interface] = $service;
				
				static::saveCfg();
				
				return static::$services[$service_interface];
			}
		}
		
		if(array_key_exists($service_interface, static::$config)) {
			$module_name = static::$config[$service_interface];
			if(Application_Modules::moduleIsActivated($module_name)) {
				
				$service = Application_Modules::moduleInstance( $module_name );
				static::$services[$service_interface] = $service;
				
				return $service;
			}
		}
		
		$meta_info = static::getServiceMetaInfo($service_interface);
		
		
		if($meta_info->isMandatory()) {
			throw new Exception('Mandatory service '.$service_interface.' is not available');
		}
		
		return null;
	}
	
}