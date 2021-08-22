<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\UI_messages;

/**
 *
 */
class Application extends Jet_Application
{

	/**
	 * @var ?Mvc_Layout
	 */
	protected static ?Mvc_Layout $layout = null;

	/**
	 * @var string
	 */
	protected static string $current_part = '';

	/**
	 * @var Locale[]|null
	 */
	protected static ?array $locales = null;

	/**
	 * @var Locale|null
	 */
	protected static ?Locale $current_locale = null;

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

	/**
	 * @return array
	 */
	public static function getParts(): array
	{
		return [
			'bases'         => [
				'label' => Tr::_( 'Bases', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'compass',
				'class' => 'Bases',
			],
			'pages'         => [
				'label' => Tr::_( 'Pages', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'file-code',
				'class' => 'Pages',
			],
			'data_model'    => [
				'label' => Tr::_( 'DataModel', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'database',
				'class' => 'DataModels',
			],
			'menus'         => [
				'label' => Tr::_( 'Menus', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'sitemap',
				'class' => 'Menus',
			],
			'modules'       => [
				'label' => Tr::_( 'Modules', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'boxes',
				'class' => 'Modules',
			],
			'module_wizard' => [
				'label' => Tr::_( 'Module wizard', [], Tr::COMMON_NAMESPACE ),
				'icon'  => 'magic',
				'class' => 'ModuleWizards',
			],
		];
	}

	/**
	 * @param $part
	 */
	public static function setCurrentPart( string $part ): void
	{
		static::$current_part = $part;
		Tr::setCurrentNamespace( $part );
	}

	/**
	 * @return string
	 */
	public static function getCurrentPart(): string
	{
		return static::$current_part;
	}

	/**
	 * @return Mvc_View
	 */
	public static function getGeneralView(): Mvc_View
	{
		return new Mvc_View( SysConf_Path::getBase() . 'views/' );
	}

	/**
	 * @param string|null $part
	 *
	 * @return Mvc_View
	 */
	public static function getView( ?string $part = null ): Mvc_View
	{
		if( !$part ) {
			$part = static::getCurrentPart();
		}
		return new Mvc_View( SysConf_Path::getApplication() . 'Parts/' . $part . '/views/' );
	}

	/**
	 * @param string $script
	 *
	 * @return Mvc_Layout
	 */
	public static function getLayout( string $script = 'default' ): Mvc_Layout
	{
		if( !static::$layout ) {
			static::$layout = new Mvc_Layout( SysConf_Path::getBase() . 'layouts/', $script );
			Mvc_Layout::setCurrentLayout( static::$layout );
		}

		return static::$layout;
	}

	/**
	 * @param string $output
	 * @param null|string $position
	 * @param null|int $position_order
	 */
	public static function output( string $output, ?string $position = null, ?int $position_order = null ): void
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
	public static function renderLayout(): void
	{
		echo static::getLayout()->render();
	}


	/**
	 * @param string|null $part
	 */
	public static function handleAction( ?string $part = null ): void
	{
		if( !$part ) {
			$part = static::$current_part;
		}

		$action = Http_Request::GET()->getString( 'action' );

		if(
			!$action ||
			str_contains( $action, '.' )
		) {
			return;
		}

		$controller = SysConf_Path::getApplication() . 'Parts/' . $part . '/controllers/' . $action . '.php';

		if( !IO_File::exists( $controller ) ) {
			return;
		}

		/** @noinspection PhpIncludeInspection */
		require $controller;
	}

	/**
	 * @param Exception $e
	 * @param Form|null $form
	 */
	public static function handleError( Exception $e, Form $form = null ): void
	{
		$error_message = Tr::_( 'Something went wrong!<br/><br/>%error%',
			[
				'error' => $e->getMessage()
			], Tr::COMMON_NAMESPACE );

		if( $form ) {
			$form->setCommonMessage( UI_messages::createDanger( $error_message ) );
		} else {
			UI_messages::danger( $error_message );
		}

	}

}