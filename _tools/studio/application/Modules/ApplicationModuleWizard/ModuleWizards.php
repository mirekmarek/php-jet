<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard;

use Jet\BaseObject;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\Tr;

use JetStudio\JetStudio;


class ModuleWizards extends BaseObject
{
	public const WIZARD_NAMESPACE = __NAMESPACE__;
	protected static null|bool|Wizard $current_wizard = null;
	protected static ?string $base_path = null;

	
	public static function getBasePath(): string
	{
		if( !static::$base_path ) {
			static::$base_path = __DIR__ . '/Wizards/';
		}

		return static::$base_path;
	}

	public static function setBasePath( string $base_path ): void
	{
		static::$base_path = $base_path;
	}


	public static function getCurrentWizard(): bool|Wizard
	{
		if( static::$current_wizard === null ) {
			$name = Http_Request::GET()->getString( 'wizard' );

			static::$current_wizard = false;

			if(
				$name &&
				($wizard = static::get( $name ))
			) {
				static::$current_wizard = $wizard;
			}
		}

		return static::$current_wizard;
	}

	public static function get( string $name ) : ?Wizard
	{
		$list = static::getList();

		if( !isset( $list[$name] ) ) {
			return null;
		}

		return $list[$name];
	}

	
	public static function getList(): array
	{
		$base_path = static::getBasePath();
		$list = IO_Dir::getList( $base_path, '*', true, false );
		
		
		$res = [];

		foreach( $list as $path => $name ) {
			$class_name = ModuleWizards::WIZARD_NAMESPACE . '\\' . $name . '\\Main';
			
			require_once static::getBasePath().$name.'/Main.php';
			require_once static::getBasePath().$name.'/Controller.php';
			
			/**
			 * @var Wizard $wizard
			 */
			$wizard = new $class_name();

			$res[$wizard->getName()] = $wizard;
		}

		return $res;
	}


	public static function getActionUrl( string $action, array $custom_get_params = [] ) : string
	{

		$get_params = [];
		$get_params['wizard'] = static::getCurrentWizard()->getName();
		$get_params['wizard_action'] = $action;

		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}
		
		return JetStudio::getModuleManifest('ApplicationModuleWizard')->getURL().'?'.http_build_query( $get_params );
	}


	public static function handleAction(): void
	{
		$wizard = ModuleWizards::getCurrentWizard();

		if( $wizard ) {
			Tr::setCurrentDictionary( $wizard->getTrNamespace() );
			$wizard->init();

			$wizard->handle();
		}

	}
}