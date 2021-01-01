<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class UI
{

	/**
	 * @var ?string
	 */
	protected static ?string $views_dir = null;

	/**
	 * @var string
	 */
	protected static string $translator_namespace = Tr::COMMON_NAMESPACE;


	/**
	 * @return string
	 */
	public static function getViewsDir() : string
	{
		if(!static::$views_dir) {
			static::$views_dir = SysConf_PATH::APPLICATION().'views/UI/';
		}
		return static::$views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public static function setViewsDir( string $views_dir ) : void
	{
		static::$views_dir = $views_dir;
	}

	/**
	 * @return Mvc_View
	 */
	public static function getView() : Mvc_View
	{
		$view = Mvc_Factory::getViewInstance( static::getViewsDir() );

		return $view;
	}


	/**
	 * @return string
	 */
	public static function getTranslatorNamespace() : string
	{
		return static::$translator_namespace;
	}

	/**
	 * @param string $translator_namespace
	 */
	public static function setTranslatorNamespace( string $translator_namespace ) : void
	{
		static::$translator_namespace = $translator_namespace;
	}

	/**
	 * @param string $text
	 * @param array  $data
	 *
	 * @return string
	 */
	public static function _( string $text, array $data = [] ) : string
	{
		return Tr::_( $text, $data, static::getTranslatorNamespace() );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button
	 */
	public static function button( string $label = '' ) : UI_button
	{
		return new UI_button( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_save
	 */
	public static function button_save( string $label = '' ) : UI_button_save
	{
		return new UI_button_save( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_goBack
	 */
	public static function button_goBack( string $label = '' ) : UI_button_goBack
	{
		return new UI_button_goBack( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_edit
	 */
	public static function button_edit( string $label = '' ) : UI_button_edit
	{
		return new UI_button_edit( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_delete
	 */
	public static function button_delete( string $label = '' ) : UI_button_delete
	{
		return new UI_button_delete( $label );
	}


	/**
	 * @param string $label
	 *
	 * @return UI_button_create
	 */
	public static function button_create( string $label ) : UI_button_create
	{
		return new UI_button_create( $label );
	}

	/**
	 * @param string $icon
	 *
	 * @return UI_icon
	 */
	public static function icon( string $icon ) : UI_icon
	{
		return new UI_icon( $icon );
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 *
	 * @return UI_dialog
	 */
	public static function dialog( string $id, string $title, int $width ) : UI_dialog
	{
		return new UI_dialog( $id, $title, $width );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_localeLabel
	 */
	public static function localeLabel( Locale $locale ) : UI_localeLabel
	{
		return new UI_localeLabel( $locale);
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_flag
	 */
	public static function flag( Locale $locale ) : UI_flag
	{
		return new UI_flag( $locale );
	}


	/**
	 * @param Locale $locale
	 *
	 * @return UI_locale
	 */
	public static function locale( Locale $locale ) : UI_locale
	{
		return new UI_locale( $locale );
	}


	/**
	 * @param array       $tabs
	 * @param callable    $tab_url_creator
	 * @param string|null $selected_tab_id
	 *
	 * @return UI_tabs
	 */
	public static function tabs( array $tabs,
	                             callable $tab_url_creator,
	                             string|null $selected_tab_id=null ) : UI_tabs
	{
		return new UI_tabs( $tabs, $tab_url_creator, $selected_tab_id );
	}

	/**
	 * @param string $id
	 * @param array $tabs
	 * @param string|null $selected_tab_id
	 *
	 * @return UI_tabsJS
	 */
	public static function tabsJS( string $id, array $tabs, string|null $selected_tab_id=null ) : UI_tabsJS
	{
		return new UI_tabsJS( $id, $tabs, $selected_tab_id );
	}


	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @return UI_searchField
	 */
	public static function searchField( string $name, string $value ) : UI_searchField
	{
		return new UI_searchField( $name, $value );
	}
}
