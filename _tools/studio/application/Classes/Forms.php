<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Http_Request;
use Jet\SysConf_URI;


class Forms {
	
	/**
	 * @var Forms_Class|null|bool
	 */
	protected static Forms_Class|null|bool $current_class = null;
	
	/**
	 * @var Forms_Class_Property|bool|null
	 */
	protected static Forms_Class_Property|bool|null $current_property = null;
	
	/**
	 * @var Forms_Class[]
	 */
	protected static ?array $classes = null;
	
	/**
	 * @var Forms_Class[]
	 */
	protected static ?array $namespaces = null;
	
	
	/**
	 * @return array
	 */
	public static function load_getDirs(): array
	{
		return [
			ProjectConf_Path::getApplicationClasses(),
			ProjectConf_Path::getApplicationModules()
		];
		
	}
	
	/**
	 * @param bool $reload
	 * @return Forms_Class[]
	 */
	public static function load( bool $reload = false ): array
	{
		if( $reload ) {
			static::$classes = null;
		}
		
		if( static::$classes === null ) {
			static::$classes = [];
			
			$finder = new Forms_ClassFinder(
				static::load_getDirs()
			);
			
			$finder->find();
			
			static::$classes = $finder->getClasses();
		}
		
		return static::$classes;
	}
	
	/**
	 * @return Forms_Class[]
	 */
	public static function getProblematicClasses(): array
	{
		static::load();
		
		$problems = [];
		
		foreach( static::$classes as $class ) {
			if( $class->getError() ) {
				$problems[] = $class;
			}
		}
		
		return $problems;
	}
	
	/**
	 * @return Forms_Namespace[]
	 */
	public static function getNamespaces(): array
	{
		
		if( static::$namespaces === null ) {
			static::$namespaces = [];
			$app_ns = new Forms_Namespace(
				Project::getApplicationNamespace(),
				ProjectConf_Path::getApplicationClasses()
			);
			
			static::$namespaces[$app_ns->getNamespace()] = $app_ns;
			
			foreach( Modules::getModules() as $module ) {
				$ns = new Forms_Namespace(
					rtrim( $module->getNamespace(), '\\' ),
					$module->getModuleDir()
				);
				
				static::$namespaces[$ns->getNamespace()] = $ns;
			}
		}
		
		return static::$namespaces;
	}
	
	
	/**
	 * @return Forms_Class[]
	 */
	public static function getClasses(): array
	{
		return static::load();
	}
	
	
	/**
	 * @param string $action
	 *
	 * @return string
	 */
	public static function getActionUrl( string $action ): string
	{
		
		$get_params = [];
		
		if( static::getCurrentClassName() ) {
			$get_params['class'] = static::getCurrentClassName();
		}
		
		if( static::getCurrentPropertyName() ) {
			$get_params['property'] = static::getCurrentPropertyName();
		}
		
		
		$get_params['action'] = $action;
		
		return SysConf_URI::getBase() . 'forms.php?' . http_build_query( $get_params );
	}
	
	/**
	 * @param string $class
	 * @param string $property
	 * @return string
	 */
	public static function getDefinitionUrl( string $class, string $property ): string
	{
		
		$get_params = [];
		
		$get_params['class'] = $class;
		$get_params['property'] = $property;
		
		
		return SysConf_URI::getBase() . 'forms.php?' . http_build_query( $get_params );
	}
	
	
	/**
	 * @param string $name
	 *
	 * @return Forms_Class|null
	 */
	public static function getClass( string $name ): Forms_Class|null
	{
		static::load();
		
		if( !isset( static::$classes[$name] ) ) {
			return null;
		}
		
		return static::$classes[$name];
	}
	
	/**
	 * @return Forms_Class|null|bool
	 */
	public static function getCurrentClass(): Forms_Class|null|bool
	{
		if( static::$current_class === null ) {
			$id = Http_Request::GET()->getString( 'class' );
			
			static::$current_class = false;
			
			if(
				$id &&
				($item = static::getClass( $id ))
			) {
				static::$current_class = $item;
			}
		}
		
		return static::$current_class;
	}
	
	/**
	 * @return string|bool
	 */
	public static function getCurrentClassName() : string|bool
	{
		if( static::getCurrentClass() ) {
			return static::getCurrentClass()->getFullClassName();
		}
		
		return false;
	}
	
	
	/**
	 * @return Forms_Class_Property|null
	 */
	public static function getCurrentProperty() : Forms_Class_Property|null
	{
		if( static::$current_property === null ) {
			static::$current_property = false;
			
			if( ($class = static::getCurrentClass()) ) {
				$name = Http_Request::GET()->getString( 'property' );

				static::$current_property = $class->getProperties()[$name]??false;
			}
		}
		
		return static::$current_property ? : null;
	}
	
	/**
	 * @return string|null
	 */
	public static function getCurrentPropertyName(): string|null
	{
		return static::getCurrentProperty()?->getName();
		
	}
	
}