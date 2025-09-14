<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

use ReflectionClass;

class Application_Service_List {
	
	/**
	 * @var array<string,Application_Service_MetaInfo>|null
	 */
	protected ?array $services_meta_info = null;
	/**
	 * @var array<string,string|array<string>>|null
	 */
	protected ?array $config = null;
	
	/**
	 * @var array<string,Application_Module|Application_Module[]>
	 */
	protected array $services = [];
	
	protected string $cfg_file_path = '';
	protected string $group = '';
	
	/**
	 * @var array<string,array<string>>|null
	 */
	protected static ?array $possible_service_map = null;
	/**
	 * @var array<string,Application_Service_MetaInfo>|null
	 */
	protected static ?array $service_meta_infos = null;
	
	
	
	public function __construct( string $cfg_file_path, string $group )
	{
		$this->cfg_file_path = $cfg_file_path;
		$this->group = $group;
	}
	
	/**
	 * @return Application_Service_MetaInfo[]
	 */
	public function getServicesMetaInfo() : array
	{
		if($this->services_meta_info===null) {
			$this->services_meta_info = [];
			
			foreach( static::getServiceMetaInfos() as $service_meta_info) {
				if($service_meta_info->getGroup() === $this->group) {
					$this->services_meta_info[$service_meta_info->getInterfaceClassName()] = $service_meta_info;
				}
			}
			
			uasort( $this->services_meta_info, function( Application_Service_MetaInfo $a, Application_Service_MetaInfo $b ) {
				return strcmp( $a->getName(), $b->getName() );
			} );
		}
		
		return $this->services_meta_info;
	}
	
	
	public function loadCfg() : void
	{
		if($this->config===null) {
			
			if(!IO_File::exists($this->cfg_file_path)) {
				$this->config = [];
				$this->saveCfg();
			}
			
			$this->config = require $this->cfg_file_path;
		}
	}
	
	public function saveCfg() : void
	{
		$this->loadCfg();
		
		IO_File::writeDataAsPhp(
			$this->cfg_file_path,
			$this->config
		);
	}
	
	
	/**
	 * @return array<string,string|array<string>>
	 */
	public function getConfig() : array
	{
		$this->loadCfg();
		
		return $this->config;
	}
	
	/**
	 * @param string $interface_class_name
	 * @param string|array<string> $module_class_name
	 * @return void
	 */
	public function setServiceConfig( string $interface_class_name, string|array $module_class_name ) : void
	{
		$this->loadCfg();
		
		$this->config[$interface_class_name] = $module_class_name;
	}
	
	
	public function getServiceMetaInfo( string $interface_class_name ) : ?Application_Service_MetaInfo
	{
		$this->getServicesMetaInfo();
		
		if(!isset( $this->services_meta_info[$interface_class_name ])) {
			throw new Exception('Application Service '.$interface_class_name.' is not registered');
		}
		
		return $this->services_meta_info[$interface_class_name ];
	}
	
	
	public function get( string $interface_class_name ) : ?Application_Module
	{
		if(isset( $this->services[$interface_class_name])) {
			return $this->services[$interface_class_name];
		}
		
		$this->loadCfg();
		if(!array_key_exists($interface_class_name, $this->config)) {
			
			$meta_info = $this->getServiceMetaInfo( $interface_class_name );
			
			foreach( static::findPossibleModules( $interface_class_name, $meta_info->getModuleNamePrefix() ) as $service) {
				
				$this->config[$interface_class_name] = $service->getModuleManifest()->getName();
				$this->services[$interface_class_name] = $service;
				
				$this->saveCfg();
				
				return $this->services[$interface_class_name];
			}
		}
		
		if(array_key_exists($interface_class_name, $this->config)) {
			$module_name = $this->config[$interface_class_name];
			if(Application_Modules::moduleIsActivated($module_name)) {
				
				$service = Application_Modules::moduleInstance( $module_name );
				$this->services[$interface_class_name] = $service;
				
				return $service;
			}
		}
		
		$meta_info = $this->getServiceMetaInfo($interface_class_name);
		
		
		if($meta_info->isMandatory()) {
			throw new Exception('Mandatory Application Service '.$interface_class_name.' is not available');
		}
		
		return null;
	}
	
	/**
	 * @param string $interface_class_name
	 *
	 * @return array<string,Application_Module>
	 */
	public function getList( string $interface_class_name ) : array
	{
		if(isset( $this->services[$interface_class_name])) {
			return $this->services[$interface_class_name];
		}
		
		$this->loadCfg();
		if(!array_key_exists($interface_class_name, $this->config)) {
			
			$meta_info = $this->getServiceMetaInfo( $interface_class_name );
			
			$this->config[$interface_class_name] = [];
			$this->services[$interface_class_name] = [];
			
			
			foreach( static::findPossibleModules( $interface_class_name, $meta_info->getModuleNamePrefix() ) as $service) {
				$name = $service->getModuleManifest()->getName();
				
				$this->config[$interface_class_name][] = $name;
				$this->services[$interface_class_name][$name] = $service;
			}
			
			$this->saveCfg();
			
		}
		
		
		$module_names = $this->config[$interface_class_name];
		
		$this->services[$interface_class_name] = [];
		foreach($module_names as $module_name) {
			if(Application_Modules::moduleIsActivated($module_name)) {
				$this->services[$interface_class_name][$module_name] = Application_Modules::moduleInstance( $module_name );
			}
		}
		
		if(!$this->services[$interface_class_name]) {
			$meta_info = $this->getServiceMetaInfo($interface_class_name);
			
			
			if($meta_info->isMandatory()) {
				throw new Exception('Mandatory Application Service '.$interface_class_name.' is not available');
			}
		}
		
		
		return $this->services[$interface_class_name];
	}
	
	
	/**
	 * @param string $interface_class_name
	 * @param string|null $name_prefix
	 *
	 * @return Application_Module[]
	 */
	public static function findPossibleModules( string $interface_class_name, ?string $name_prefix=null ) : array
	{
		if(static::$possible_service_map===null) {
			static::$possible_service_map = [];
			
			$modules = Application_Modules::activatedModulesList();
			
			foreach( $modules as $module_name => $module_info ) {
				$module = Application_Modules::moduleInstance( $module_name );
				
				$implements = class_implements( $module , false);
				
				foreach($implements as $ifc) {
					if(!isset( static::$possible_service_map[$ifc])) {
						static::$possible_service_map[$ifc] = [];
					}
					
					static::$possible_service_map[$ifc][$module_name] = $module;
				}
				
				$parents = class_parents( $module, false );
				foreach($parents as $parent) {
					if(!isset( static::$possible_service_map[$parent])) {
						static::$possible_service_map[$parent] = [];
					}
					
					static::$possible_service_map[$parent][$module_name] = $module;
				}
				
			}
		}
		
		
		if(!isset( static::$possible_service_map[$interface_class_name])) {
			return [];
		}
		
		if(!$name_prefix) {
			return static::$possible_service_map[$interface_class_name];
		}
		
		$services = [];
		
		foreach( static::$possible_service_map[$interface_class_name] as $name=> $module ) {
			if(str_starts_with($name, $name_prefix)) {
				$services[$name] = $module;
			}
		}
		
		return $services;
	}
	
	/**
	 * @return Application_Service_MetaInfo[]
	 */
	public static function getServiceMetaInfos() : array
	{
		if(static::$service_meta_infos===null) {
			$finder = new class {
				/**
				 * @var Application_Service_MetaInfo[]
				 */
				protected array $classes = [];
				protected string $root_dir = '';
				protected string $root_class_name = '';
				
				public function __construct()
				{
					$this->root_dir = SysConf_Path::getApplication() . 'Classes/Application/Service/';
					$this->root_class_name = '\\JetApplication\\Application_Service_';
					$this->find();
				}
				
				
				public function find(): void
				{
					$this->readDir( $this->root_dir );
					
					ksort( $this->classes );
				}
				
				protected function readDir( string $dir ): void
				{
					$dirs = IO_Dir::getList( $dir, '*', true, false );
					$files = IO_Dir::getList( $dir, '*.php', false, true );
					
					foreach( $files as $path => $name ) {
						$class = str_replace($this->root_dir, '', $path);
						$class = str_replace('.php', '', $class);
						
						$class = str_replace('/', '_', $class);
						$class = str_replace('\\', '_', $class);
						
						$class = $this->root_class_name.$class;
						
						$reflection = new ReflectionClass( $class );
						
						if(
							$reflection->isInterface() ||
							$reflection->isAbstract()
						) {
							$attributes = Attributes::getClassDefinition(
								$reflection,
								Application_Service_MetaInfo::class
							);
							
							if($attributes) {
								$this->classes[$class] = Application_Service_MetaInfo::create( $reflection, $attributes );
							}
						}
					}
					
					foreach( $dirs as $path => $name ) {
						$this->readDir( $path );
					}
				}
				
				/**
				 * @return Application_Service_MetaInfo[]
				 */
				public function getClasses(): array
				{
					return $this->classes;
				}
			};
			
			static::$service_meta_infos = $finder->getClasses();
		}
		
		return static::$service_meta_infos;
	}
	
	
}