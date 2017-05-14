<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class UI
{

	/**
	 * @var string
	 */
	protected static $views_dir = JET_PATH_APPLICATION.'views/UI/';

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
	 * @return UI_button_save
	 */
	public static function button_save( $label = '' )
	{
		return new UI_button_save( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_goBack
	 */
	public static function button_goBack( $label = '' )
	{
		return new UI_button_goBack( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_edit
	 */
	public static function button_edit( $label = '' )
	{
		return new UI_button_edit( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_delete
	 */
	public static function button_delete( $label = '' )
	{
		return new UI_button_delete( $label );
	}


	/**
	 * @param string $label
	 *
	 * @return UI_button_create
	 */
	public static function button_create( $label )
	{
		return new UI_button_create( $label );
	}

	/**
	 * @param string $icon
	 *
	 * @return UI_icon
	 */
	public static function icon( $icon )
	{
		return new UI_icon( $icon );
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param int    $width
	 *
	 * @return UI_dialog
	 */
	public static function dialog( $id, $title, $width )
	{
		return new UI_dialog( $id, $title, $width );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_localeLabel
	 */
	public static function localeLabel( Locale $locale )
	{
		return new UI_localeLabel( $locale);
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_flag
	 */
	public static function flag( Locale $locale )
	{
		return new UI_flag( $locale );
	}

	/**
	 * @param array $tabs
	 *
	 * @return UI_tabs
	 */
	public static function tabs( array $tabs )
	{
		return new UI_tabs( $tabs );
	}

	/**
	 * @param string $name
	 *
	 * @return UI_searchForm
	 */
	public static function searchForm( $name )
	{
		return new UI_searchForm( $name );
	}
}
