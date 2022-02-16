<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\SysConf_Path;
use Jet\SysConf_URI;
use Jet\Tr;

/**
 *
 */
class ModuleWizards extends BaseObject implements Application_Part
{
	const WIZARD_NAMESPACE = 'JetStudio\ModuleWizard';


	/**
	 * @var null|bool|ModuleWizard
	 */
	protected static null|bool|ModuleWizard $__current_wizard = null;

	/**
	 * @var ?string
	 */
	protected static ?string $base_path = null;


	/**
	 * @return string
	 */
	public static function getBasePath(): string
	{
		if( !static::$base_path ) {
			static::$base_path = SysConf_Path::getApplication() . 'Parts/module_wizard/wizards/';
		}

		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( string $base_path ): void
	{
		static::$base_path = $base_path;
	}


	/**
	 * @return bool|ModuleWizard
	 */
	public static function getCurrentWizard(): bool|ModuleWizard
	{
		if( static::$__current_wizard === null ) {
			$name = Http_Request::GET()->getString( 'wizard' );

			static::$__current_wizard = false;

			if(
				$name &&
				($wizard = static::get( $name ))
			) {
				static::$__current_wizard = $wizard;
			}
		}

		return static::$__current_wizard;
	}

	/**
	 * @param string $name
	 *
	 * @return ModuleWizard|null
	 */
	public static function get( string $name ) : ?ModuleWizard
	{
		$list = static::getList();

		if( !isset( $list[$name] ) ) {
			return null;
		}

		return $list[$name];
	}


	/**
	 * @return ModuleWizard[]
	 */
	public static function getList(): array
	{
		$base_path = static::getBasePath();

		$list = IO_Dir::getList( $base_path, '*', true, false );

		$res = [];

		foreach( $list as $path => $name ) {
			$class_name = ModuleWizards::WIZARD_NAMESPACE . '\\' . $name . '\\Wizard';

			/**
			 * @var ModuleWizard $wizard
			 */
			$wizard = new $class_name();

			$res[$wizard->getName()] = $wizard;
		}

		return $res;
	}

	/**
	 * @param string $action
	 * @param array $custom_get_params
	 *
	 * @return string
	 */
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

		return SysConf_URI::getBase() . 'module_wizard.php?' . http_build_query( $get_params );
	}

	/**
	 *
	 */
	public static function handleAction(): void
	{
		$wizard = ModuleWizards::getCurrentWizard();

		if( $wizard ) {
			Tr::setCurrentDictionary( $wizard->getTrNamespace() );
			$wizard->init();

			$wizard->handleAction();
		}

	}
}