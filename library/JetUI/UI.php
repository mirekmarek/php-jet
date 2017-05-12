<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\Tr;
use Jet\Locale;
use Jet\Mvc_View;

//TODO: dukladne proverit, kde jeste neni konfigurovatelny rederer
/**
 * Class UI
 * @package JetUI
 */
class UI
{

	/**
	 * @var string
	 */
	protected static $views_dir = JET_APPLICATION_PATH.'views/UI/';

	/**
	 * @var string
	 */
	protected static $translator_namespace = Tr::COMMON_NAMESPACE;


	/**
	 * @return string
	 */
	public static function getViewsDir()
	{
		return self::$views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public static function setViewsDir( $views_dir )
	{
		self::$views_dir = $views_dir;
	}

	/**
	 * @return Mvc_View
	 */
	public static function getView()
	{
		$view = new Mvc_View( static::getViewsDir() );

		return $view;
	}


	/**
	 * @return string
	 */
	public static function getTranslatorNamespace()
	{
		return self::$translator_namespace;
	}

	/**
	 * @param string $translator_namespace
	 */
	public static function setTranslatorNamespace( $translator_namespace )
	{
		self::$translator_namespace = $translator_namespace;
	}

	/**
	 * @param string $text
	 * @param array  $data
	 *
	 * @return string
	 */
	public static function _( $text, $data = [] ) {
		return Tr::_( $text, $data, static::getTranslatorNamespace() );
	}


	/**
	 * @param string $label
	 *
	 * @return button_save
	 */
	public static function button_save( $label = '' )
	{
		return new button_save( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return button_goBack
	 */
	public static function button_goBack( $label = '' )
	{
		return new button_goBack( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return button_edit
	 */
	public static function button_edit( $label = '' )
	{
		return new button_edit( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return button_delete
	 */
	public static function button_delete( $label = '' )
	{
		return new button_delete( $label );
	}


	/**
	 * @param string $label
	 *
	 * @return button_create
	 */
	public static function button_create( $label )
	{
		return new button_create( $label );
	}

	/**
	 * @param string $icon
	 *
	 * @return icon
	 */
	public static function icon( $icon )
	{
		return new icon( $icon );
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param int    $width
	 *
	 * @return dialog
	 */
	public static function dialog( $id, $title, $width )
	{
		return new dialog( $id, $title, $width );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return localeLabel
	 */
	public static function localeLabel( Locale $locale )
	{
		return new localeLabel( $locale);
	}

	/**
	 * @param Locale $locale
	 *
	 * @return flag
	 */
	public static function flag( Locale $locale )
	{
		return new flag( $locale );
	}

	/**
	 * @param array $tabs
	 *
	 * @return tabs
	 */
	public static function tabs( array $tabs )
	{
		return new tabs( $tabs );
	}

	/**
	 * @param string $name
	 *
	 * @return searchForm
	 */
	public static function searchForm( $name )
	{
		return new searchForm( $name );
	}
}
