<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
use Jet\MVC_Layout;
use Jet\MVC_View;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;

/**
 *
 */
class Application extends Jet_Application
{

	/**
	 * @var ?MVC_Layout
	 */
	protected static ?MVC_Layout $layout = null;

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
				'label' => Tr::_( 'Bases', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'compass',
				'class' => 'Bases',
			],
			'pages'         => [
				'label' => Tr::_( 'Pages', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'file-code',
				'class' => 'Pages',
			],
			'data_model'    => [
				'label' => Tr::_( 'DataModel', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'database',
				'class' => 'DataModels',
			],
			'forms'    => [
				'label' => Tr::_( 'Forms', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'pencil-ruler',
				'class' => 'Forms',
			],
			'menus'         => [
				'label' => Tr::_( 'Menus', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'sitemap',
				'class' => 'Menus',
			],
			'modules'       => [
				'label' => Tr::_( 'Modules', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'boxes',
				'class' => 'Modules',
			],
			'module_wizard' => [
				'label' => Tr::_( 'Module wizard', [], Translator::COMMON_DICTIONARY ),
				'icon'  => 'magic',
				'class' => 'ModuleWizards',
			],
		];
	}

	/**
	 * @param string $part
	 */
	public static function setCurrentPart( string $part ): void
	{
		static::$current_part = $part;
		Tr::setCurrentDictionary( $part );
	}

	/**
	 * @return string
	 */
	public static function getCurrentPart(): string
	{
		return static::$current_part;
	}

	/**
	 * @return MVC_View
	 */
	public static function getGeneralView(): MVC_View
	{
		return new MVC_View( SysConf_Path::getBase() . 'application/views/' );
	}

	/**
	 * @param string|null $part
	 *
	 * @return MVC_View
	 */
	public static function getView( ?string $part = null ): MVC_View
	{
		if( !$part ) {
			$part = static::getCurrentPart();
		}
		return new MVC_View( SysConf_Path::getApplication() . 'Parts/' . $part . '/views/' );
	}

	/**
	 * @param string $script
	 *
	 * @return MVC_Layout
	 */
	public static function getLayout( string $script = 'default' ): MVC_Layout
	{
		if( !static::$layout ) {
			static::$layout = new MVC_Layout( SysConf_Path::getBase() . 'application/layouts/', $script );
			MVC_Layout::setCurrentLayout( static::$layout );
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
			], Translator::COMMON_DICTIONARY );

		if( $form ) {
			$form->setCommonMessage( UI_messages::createDanger( $error_message ) );
		} else {
			UI_messages::danger( $error_message );
		}

	}

}