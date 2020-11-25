<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Exception;
use Jet\Form;
use Jet\Application as Jet_Application;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\Locale;
use Jet\Mvc_Layout;
use Jet\Mvc_View;
use Jet\SysConf_PATH;
use Jet\Tr;
use Jet\UI_messages;

/**
 *
 */
class Application extends Jet_Application {

	/**
	 * @var Mvc_Layout
	 */
	protected static $layout;

	/**
	 * @var string
	 */
	protected static $current_part = '';

	/**
	 * @var Locale[]
	 */
	protected static $locales;

	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * @return Locale[]
	 */
	public static function getLocales()
	{
		if(!static::$locales) {
			static::$locales = [];
			$locales  = require SysConf_PATH::CONFIG().'locales.php';

			foreach( $locales as $l ) {
				static::$locales[$l] = new Locale($l);
			}

		}

		return static::$locales;
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale()
	{
		if(!static::$current_locale) {
			$cookie_name = 'locale';

			$locales = static::getLocales();

			foreach( static::getLocales() as $locale ) {
				static::$current_locale = $locale;
				break;
			}

			if(
				isset($_COOKIE[$cookie_name]) &&
				isset($locales[$_COOKIE[$cookie_name]])
			) {
				static::$current_locale = $locales[$_COOKIE[$cookie_name]];
			}

			$GET = Http_Request::GET();
			if(
				($set_locale=$GET->getString('locale')) &&
				isset($locales[$set_locale])
			) {
				static::$current_locale = $locales[$set_locale];
			}


			setcookie($cookie_name, static::$current_locale->toString(), time() + (86400 * 365) );
		}


		return static::$current_locale;
	}

	/**
	 * @return array
	 */
	public static function getParts()
	{
		return [
			'sites'      => [
					'label' => Tr::_('Sites',[], Tr::COMMON_NAMESPACE),
					'icon'  => 'compass',
					'class' => 'Sites',
				],
			'pages'      => [
					'label' => Tr::_('Pages',[], Tr::COMMON_NAMESPACE),
					'icon'  => 'file-code',
					'class' => 'Pages',
				],
			'data_model' => [
				'label' => Tr::_('DataModel', [], Tr::COMMON_NAMESPACE),
				'icon'  => 'database',
				'class' => 'DataModels',
			],
			'menus'      => [
					'label' => Tr::_('Menus', [], Tr::COMMON_NAMESPACE),
					'icon'  => 'sitemap',
					'class' => 'Menus',
				],
			'modules'    => [
					'label' => Tr::_('Modules', [], Tr::COMMON_NAMESPACE),
					'icon'  => 'boxes',
					'class' => 'Modules',
				]
		];
	}

	/**
	 * @param $part
	 */
	public static function setCurrentPart( $part )
	{
		static::$current_part = $part;
		Tr::setCurrentNamespace( $part );
	}

	/**
	 * @return string
	 */
	public static function getCurrentPart()
	{
		return static::$current_part;
	}

	/**
	 * @return Mvc_View
	 */
	public static function getGeneralView()
	{
		$view = new Mvc_View( SysConf_PATH::APPLICATION().'views/' );

		return $view;
	}

	/**
	 * @return Mvc_View
	 */
	public static function getView()
	{
		$view = new Mvc_View( SysConf_PATH::APPLICATION().'Parts/'.static::$current_part.'/views/' );

		return $view;
	}

	/**
	 * @param string $script
	 *
	 * @return Mvc_Layout
	 */
	public static function getLayout( $script='default' )
	{
		if(!static::$layout) {
			static::$layout = new Mvc_Layout(SysConf_PATH::APPLICATION().'layouts/', $script);
			Mvc_Layout::setCurrentLayout( static::$layout );
		}

		return static::$layout;
	}

	/**
	 * @param string $output
	 * @param null|string $position
	 * @param null|int $position_order
	 */
	public static function output( $output, $position = null, $position_order = null )
	{
		static::getLayout()->addOutputPart(
			$output,
			$position,
			$position_order
		);

	}

	/**
	 *
	 */
	public static function renderLayout()
	{
		echo static::getLayout()->render();
	}


	/**
	 * @param string|null $part
	 */
	public static function handleAction( $part=null )
	{
		if(!$part) {
			$part = static::$current_part;
		}

		$action = Http_Request::GET()->getString('action');

		if(
			!$action ||
			strpos($action, '.')!==false
		) {
			return;
		}

		$controller = SysConf_PATH::APPLICATION().'Parts/'.$part.'/controllers/'.$action.'.php';

		if(!IO_File::exists($controller)) {
			return;
		}

		/** @noinspection PhpIncludeInspection */
		require $controller;
	}

	/**
	 * @param Exception $e
	 * @param Form|null $form
	 */
	public static function handleError( Exception $e, Form $form=null)
	{
		$error_message  =Tr::_('Something went wrong!<br/><br/>%error%',
			[
				'error' => $e->getMessage()
			], Tr::COMMON_NAMESPACE );

		if($form) {
			$form->setCommonMessage( UI_messages::createDanger( $error_message ) );
		} else {
			UI_messages::danger( $error_message );
		}

	}

	/**
	 *
	 */
	public static function resetOPCache()
	{
		if(function_exists('opcache_reset')) {
			opcache_reset();
		}
	}
	
}