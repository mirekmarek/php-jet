<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Exception;
use Jet\Data_Text;
use Jet\Form;
use Jet\Application as Jet_Application;
use Jet\Form_Field;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\Locale;
use Jet\MVC_Layout;
use Jet\MVC_View;
use Jet\SysConf_Path;
use Jet\SysConf_URI;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;

/**
 *
 */
class JetStudio extends Jet_Application
{
	protected static ?MVC_Layout $layout = null;

	/**
	 * @var Locale[]|null
	 */
	protected static ?array $locales = null;
	protected static ?Locale $current_locale = null;
	
	protected static string $application_namespace = 'JetApplication';
	
	/**
	 * @var JetStudio_Module_Manifest[]|null
	 */
	protected static ?array $module_manifests = null;
	
	/**
	 * @var JetStudio_Module[]
	 */
	protected static array $module_instance = [];
	
	protected static ?JetStudio_Module $current_module = null;
	
	/**
	 * @return Locale[]
	 */
	public static function getLocales(): array
	{
		if( !static::$locales ) {
			static::$locales = [];
			$locales = require SysConf_Path::getConfig() . 'locales.php';

			foreach( $locales as $l ) {
				static::$locales[$l] = new Locale( $l );
			}

		}

		return static::$locales;
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale(): Locale
	{
		if( !static::$current_locale ) {
			$cookie_name = 'locale';

			$locales = static::getLocales();

			foreach( static::getLocales() as $locale ) {
				static::$current_locale = $locale;
				break;
			}

			if(
				isset( $_COOKIE[$cookie_name] ) &&
				isset( $locales[$_COOKIE[$cookie_name]] )
			) {
				static::$current_locale = $locales[$_COOKIE[$cookie_name]];
			}

			$GET = Http_Request::GET();
			if(
				($set_locale = $GET->getString( 'std_locale' )) &&
				isset( $locales[$set_locale] )
			) {
				static::$current_locale = $locales[$set_locale];
			}


			setcookie( $cookie_name, static::$current_locale->toString(), time() + (86400 * 365) );
		}


		return static::$current_locale;
	}

	public static function getGeneralView(): MVC_View
	{
		return new MVC_View( SysConf_Path::getBase() . 'application/views/' );
	}

	public static function initLayout( string $script = 'default' ): MVC_Layout
	{
		if( !static::$layout ) {
			static::$layout = new MVC_Layout( SysConf_Path::getBase() . 'application/layouts/', $script );
			MVC_Layout::setCurrentLayout( static::$layout );
		}

		return static::$layout;
	}

	public static function output( string $output, ?string $position = null, ?int $position_order = null ): void
	{
		static::initLayout()->addOutputPart(
			$output,
			$position,
			$position_order
		);

	}

	/**
	 *
	 */
	public static function renderLayout(): void
	{
		Translator::setCurrentDictionary( Translator::COMMON_DICTIONARY );
		echo static::initLayout()->render();
	}
	

	public static function handleError( Exception $e, ?Form $form = null ): void
	{
		$error_message = Tr::_( 'Something went wrong!<br/><br/>%error%',
			[
				'error' => $e->getMessage()
			], Translator::COMMON_DICTIONARY );

		if( $form ) {
			$form->setCommonMessage( UI_messages::createDanger( $error_message ) );
		} else {
			UI_messages::danger( $error_message );
		}

	}

	public static function setApplicationNamespace( string $application_namespace ): void
	{
		self::$application_namespace = $application_namespace;
	}
	
	public static function getApplicationNamespace(): string
	{
		return static::$application_namespace;
	}
	
	
	public static function generateIdentifier( string $name, callable $check_exists_callback ): string
	{
		
		$id = Data_Text::removeAccents( $name );
		$id = str_replace( ' ', '-', $id );
		$id = preg_replace( '/[^a-z0-9-]/i', '', $id );
		$id = strtolower( $id );
		$id = preg_replace( '~([-]{2,})~', '-', $id );
		
		$id = trim( $id, '-' );
		
		$base_id = $id;
		$i = 0;
		while( $check_exists_callback( $id ) ) {
			$i++;
			
			$id = $base_id . $i;
		}
		
		return $id;
	}
	
	
	public static function validateClassName( Form_Field $field ): bool
	{
		if( !$field->getIsRequired() ) {
			return true;
		}
		
		
		$class_name = $field->getValue();
		
		if( !$class_name ) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}
		
		if(
			!preg_match( '/^([a-zA-Z1-9\\\_]{3,})$/', $class_name ) ||
			str_contains( $class_name, '\\\\' ) ||
			str_contains( $class_name, '__' ) ||
			str_ends_with( $class_name, '\\' )
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
			
			return false;
		}
		
		return true;
		
	}
	
	public static function validateMethodName( Form_Field $field ): bool
	{
		$method_name = $field->getValue();
		
		if($method_name) {
			if(
				!preg_match( '/^([a-zA-Z1-9_]{3,})$/', $method_name ) ||
				str_contains( $method_name, '__' )
			) {
				$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
				
				return false;
			}
			
		}
		
		return true;
		
	}
	
	public static function validateControllerName( Form_Field $field ): bool
	{
		if( !$field->getIsRequired() ) {
			return true;
		}
		
		$controller_name = $field->getValue();
		
		if( !$controller_name ) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}
		
		if(
			!preg_match( '/^([a-zA-Z1-9_]{3,})$/', $controller_name ) ||
			str_contains( $controller_name, '__' )
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
			
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * @return JetStudio_Module_Manifest[]
	 */
	public static function getModuleManifests() : array
	{
		if( static::$module_manifests===null ) {
			static::$module_manifests = [];
			
			$base_dir = SysConf_Path::getApplication().'Modules/';
			$manifest_fn = 'manifest.php';
			
			foreach( IO_Dir::getSubdirectoriesList( $base_dir ) as $path=>$name ) {
				if($name[0]=='.' || $name[0]=='_') {
					continue;
				}
				
				$manifest_path = $path.$manifest_fn;
				
				if(!IO_File::isReadable($manifest_path)) {
					continue;
				}
				
				$manifest = new JetStudio_Module_Manifest(
					$name,
					'JetStudioModule\\'.$name,
					$path,
					require $manifest_path
				);
				
				static::$module_manifests[$name] = $manifest;
				
				$class_name = 'JetStudioModule\\'.$name.'\\Main';
				$module = new $class_name( $manifest );
				static::$module_instance[$name] = $module;
			}
		}
		
		uasort( static::$module_manifests, function( JetStudio_Module_Manifest $a, JetStudio_Module_Manifest $b ) {
			return $a->getSortOrder() <=> $b->getSortOrder();
		} );
		
		return static::$module_manifests;
	}
	
	public static function getModuleManifest( string $name ) : ?JetStudio_Module_Manifest
	{
		static::getModuleManifests();
		
		return static::$module_manifests[$name] ?? null;
	}
	
	/**
	 * @return JetStudio_Module[]
	 */
	public static function getModuleInstances() : array
	{
		static::getModuleManifests();
		
		return static::$module_instance;
	}
	
	public static function getModuleInstance( string $name ) : ?JetStudio_Module
	{
		static::getModuleManifests();
		
		return static::$module_instance[$name] ?? null;
	}
	
	public static function getCurrentModule() : ?JetStudio_Module
	{
		if(static::$current_module) {
			return static::$current_module;
		}
		
		$base_uri = SysConf_URI::getBase();
		
		$URI = $_SERVER['REQUEST_URI'];

		$URI = str_replace($base_uri, '', $URI);
		
		if($URI) {
			$URI = explode('/', $URI);
			$URI = $URI[0];
		}
		
		if($URI) {
			foreach( static::getModuleInstances() as $module ) {
				if($module->getManifest()->getUrlPathPart()==$URI) {
					static::$current_module = $module;
					return $module;
				}
			}
		}
		
		foreach( static::getModuleInstances() as $module ) {
			if( $module instanceof JetStudio_Module_Service_Welcome ) {
				/**
				 * @var JetStudio_Module $module
				 */
				static::$current_module = $module;
				return $module;
			}
		}
		
		return null;
	}
	
	
	public static function getServiceModule( string $service_interface_name ) : null|JetStudio_Module|JetStudio_Module_Service_AccessControl
	{
		foreach( static::getModuleInstances() as $name=>$module) {
			if( $module instanceof $service_interface_name ) {
				return $module;
			}
		}
		
		return null;
	}
	
	/**
	 * @param string $service_interface_name
	 * @return JetStudio_Module[]|JetStudio_Module_Service_AccessControl[]
	 */
	public static function getServiceModules( string $service_interface_name ) : array
	{
		$modules = [];
		foreach( static::getModuleInstances() as $name=>$module) {
			if( $module instanceof $service_interface_name ) {
				$modules[$name] = $module;
			}
		}
		
		return $modules;
	}
	
	
	public static function getModule_AccessControl() : null|JetStudio_Module|JetStudio_Module_Service_AccessControl
	{
		return static::getServiceModule( JetStudio_Module_Service_AccessControl::class );
	}
	
	public static function getModule_Welcome() : null|JetStudio_Module|JetStudio_Module_Service_Welcome
	{
		return static::getServiceModule( JetStudio_Module_Service_Welcome::class );
	}
	
	public static function getModule_ApplicationModules() : null|JetStudio_Module|JetStudio_Module_Service_ApplicationModules
	{
		return static::getServiceModule( JetStudio_Module_Service_ApplicationModules::class );
	}
	
	public static function getModule_DataModel() : null|JetStudio_Module|JetStudio_Module_Service_DataModel
	{
		return static::getServiceModule( JetStudio_Module_Service_DataModel::class );
	}
	
	public static function getModule_Forms() : null|JetStudio_Module|JetStudio_Module_Service_Forms
	{
		return static::getServiceModule( JetStudio_Module_Service_Forms::class );
	}
	
	public static function getModule_Pages() : null|JetStudio_Module|JetStudio_Module_Service_Pages
	{
		return static::getServiceModule( JetStudio_Module_Service_Pages::class );
	}
	
	public static function getModule_Menus() : null|JetStudio_Module|JetStudio_Module_Service_Menus
	{
		return static::getServiceModule( JetStudio_Module_Service_Menus::class );
	}
}