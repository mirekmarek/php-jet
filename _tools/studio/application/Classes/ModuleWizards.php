<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\SysConf_PATH;
use Jet\SysConf_URI;

/**
 *
 */
class ModuleWizards extends BaseObject implements Application_Part
{
	const WIZARD_NAMESPACE = 'JetStudio\ModuleWizard';


	/**
	 * @var ModuleWizard
	 */
	protected static $__current_wizard;

	/**
	 * @var string
	 */
	protected static $base_path;



	/**
	 * @return string
	 */
	public static function getBasePath()
	{
		if(!static::$base_path) {
			static::$base_path = SysConf_PATH::APPLICATION().'Parts/module_wizard/wizards/';
		}

		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( $base_path )
	{
		static::$base_path = $base_path;
	}


	/**
	 * @return null|ModuleWizard
	 */
	public static function getCurrentWizard()
	{
		if(static::$__current_wizard===null) {
			$name = Http_Request::GET()->getString('wizard');

			static::$__current_wizard = false;

			if(
				$name &&
				($wizard=static::get($name))
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
	public static function get( $name )
	{
		$list = static::getList();

		if(!isset($list[$name])) {
			return null;
		}

		return $list[$name];
	}


	/**
	 * @return ModuleWizard[]
	 */
	public static function getList()
	{
		$base_path = static::getBasePath();

		$list = IO_Dir::getList( $base_path, '*', true, false );

		$res = [];

		foreach( $list as $path=>$name ) {
			$class_name = ModuleWizards::WIZARD_NAMESPACE.'\\'.$name.'\\Wizard';

			/**
			 * @var ModuleWizard $wizard
			 */
			$wizard = new $class_name();

			$res[$wizard->getName()] = $wizard;
		}

		return $res;
	}

	/**
	 * @param $action
	 * @param array $custom_get_params
	 *
	 * @return string $url
	 */
	public static function getActionUrl( $action,  array $custom_get_params=[] )
	{

		$get_params = [];
		$get_params['wizard'] = static::getCurrentWizard()->getName();
		$get_params['wizard_action'] = $action;

		if($custom_get_params) {
			foreach( $custom_get_params as $k=>$v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::BASE().'module_wizard.php?'.http_build_query($get_params);
	}

	/**
	 *
	 */
	public static function handleAction()
	{
		$wizard = ModuleWizards::getCurrentWizard();

		if($wizard) {
			$wizard->init();

			$wizard->handleAction();
		}

	}
}