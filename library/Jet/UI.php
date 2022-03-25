<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected static string $translator_dictionary = Translator::COMMON_DICTIONARY;
	
	/**
	 * @return string
	 */
	public static function getTranslatorDictionary(): string
	{
		return static::$translator_dictionary;
	}

	/**
	 * @param string $translator_dictionary
	 */
	public static function setTranslatorDictionary( string $translator_dictionary ): void
	{
		static::$translator_dictionary = $translator_dictionary;
	}

	/**
	 * @param string $text
	 * @param array $data
	 *
	 * @return string
	 */
	public static function _( string $text, array $data = [] ): string
	{
		return Tr::_( $text, $data, static::getTranslatorDictionary() );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button
	 */
	public static function button( string $label = '' ): UI_button
	{
		return new UI_button( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_save
	 */
	public static function button_save( string $label = '' ): UI_button_save
	{
		return new UI_button_save( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_goBack
	 */
	public static function button_goBack( string $label = '' ): UI_button_goBack
	{
		return new UI_button_goBack( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_edit
	 */
	public static function button_edit( string $label = '' ): UI_button_edit
	{
		return new UI_button_edit( $label );
	}

	/**
	 * @param string $label
	 *
	 * @return UI_button_delete
	 */
	public static function button_delete( string $label = '' ): UI_button_delete
	{
		return new UI_button_delete( $label );
	}


	/**
	 * @param string $label
	 *
	 * @return UI_button_create
	 */
	public static function button_create( string $label ): UI_button_create
	{
		return new UI_button_create( $label );
	}

	/**
	 * @param string $icon
	 *
	 * @return UI_icon
	 */
	public static function icon( string $icon ): UI_icon
	{
		return new UI_icon( $icon );
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param string $size
	 *
	 * @return UI_dialog
	 */
	public static function dialog( string $id, string $title, string $size=UI_dialog::SIZE_DEFAULT ): UI_dialog
	{
		return new UI_dialog( $id, $title, $size );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_localeLabel
	 */
	public static function localeLabel( Locale $locale ): UI_localeLabel
	{
		return new UI_localeLabel( $locale );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_flag
	 */
	public static function flag( Locale $locale ): UI_flag
	{
		return new UI_flag( $locale );
	}


	/**
	 * @param Locale $locale
	 *
	 * @return UI_locale
	 */
	public static function locale( Locale $locale ): UI_locale
	{
		return new UI_locale( $locale );
	}


	/**
	 * @param array $tabs
	 * @param callable $tab_url_creator
	 * @param string|null $selected_tab_id
	 *
	 * @return UI_tabs
	 */
	public static function tabs( array $tabs,
	                             callable $tab_url_creator,
	                             string|null $selected_tab_id = null ): UI_tabs
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
	public static function tabsJS( string $id, array $tabs, string|null $selected_tab_id = null ): UI_tabsJS
	{
		return new UI_tabsJS( $id, $tabs, $selected_tab_id );
	}
	
	/**
	 * @param string $type
	 * @param string $text
	 * @return UI_badge
	 */
	public static function badge( string $type, string $text ): UI_badge
	{
		return new UI_badge( $type, $text );
	}
	
	/**
	 * @param Data_Tree $data
	 * @return UI_tree
	 */
	public static function tree( Data_Tree $data ) : UI_tree
	{
		$tree = new UI_tree();
		$tree->setData( $data );
		
		return $tree;
	}
}
