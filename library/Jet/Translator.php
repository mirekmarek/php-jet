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
class Translator extends BaseObject
{

	const COMMON_DICTIONARY = '_COMMON_';

	/**
	 *
	 * @var ?Translator_Backend
	 */
	protected static ?Translator_Backend $backend = null;

	/**
	 * @var string
	 */
	protected static string $current_dictionary = self::COMMON_DICTIONARY;

	/**
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;

	/**
	 * @var Translator_Dictionary[]
	 */
	protected static array $dictionaries = [];

	/**
	 *
	 * @return Translator_Backend
	 */
	public static function getBackend(): Translator_Backend
	{
		if( static::$backend === null ) {
			static::$backend = Factory_Translator::getDefaultBackendInstance();

			if(SysConf_Jet_Translator::getAutoAppendUnknownPhrase()) {
				register_shutdown_function( [
					static::class,
					'saveUpdatedDictionaries'
				] );
			}
		}

		return static::$backend;
	}

	/**
	 *
	 * @param Translator_Backend $backend
	 */
	public static function setBackend( Translator_Backend $backend ): void
	{
		if( static::$backend === null ) {
			register_shutdown_function( [
				static::class,
				'saveUpdatedDictionaries'
			] );
		}
		static::$backend = $backend;
	}

	/**
	 *
	 * @return string
	 */
	public static function getCurrentDictionary(): string
	{
		return static::$current_dictionary;
	}

	/**
	 *
	 * @param string $current_dictionary
	 */
	public static function setCurrentDictionary( string $current_dictionary ): void
	{
		static::$current_dictionary = $current_dictionary;
	}

	/**
	 *
	 * @return Locale
	 */
	public static function getCurrentLocale(): Locale
	{
		return static::$current_locale;
	}

	/**
	 *
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale ): void
	{
		static::$current_locale = $current_locale;
	}

	/**
	 *
	 */
	public static function saveUpdatedDictionaries(): void
	{
		$backend = static::getBackend();

		foreach( static::$dictionaries as $dictionary ) {
			if( $dictionary->saveRequired() ) {
				$backend->saveDictionary( $dictionary );
			}
		}
	}

	/**
	 * Gets translation of given text
	 *
	 * @param string $text
	 * @param array $data (optional) - data that replace parts of text; input array('KEY1'=>'value1') replaces %KEY1% in text for value1
	 * @param string|null $dictionary (optional)
	 * @param Locale|null $locale (optional) - target locale
	 *
	 * @return string
	 */
	public static function _( string $text, array $data = [], string|null $dictionary = null, Locale|null $locale = null ) : string
	{
		return static::getTranslation( $text, $data, $dictionary, $locale );
	}

	/**
	 * Gets translation of given text
	 *
	 *
	 * @param string $phrase
	 * @param array $data (optional) - data that replace parts of text; input array('KEY1'=>'value1') replaces %KEY1% in text for value1
	 * @param string|null $dictionary (optional)
	 * @param Locale|null $locale (optional) - target locale
	 *
	 * @return string
	 */
	public static function getTranslation( string      $phrase,
	                                       array       $data = [],
	                                       string|null $dictionary = null,
	                                       Locale|null $locale = null ): string
	{
		if(!trim($phrase)) {
			return $phrase;
		}

		if( !$dictionary ) {
			$dictionary = static::$current_dictionary;
		}

		if( $locale === null ) {
			$locale = static::$current_locale;
		}

		if(
			!$dictionary ||
			!$locale
		) {
			return Data_Text::replaceData( $phrase, $data );
		}

		if( is_string( $locale ) ) {
			$locale = new Locale( $locale );
		}

		$translation = static::loadDictionary( $dictionary, $locale )->getTranslation( $phrase, SysConf_Jet_Translator::getAutoAppendUnknownPhrase() );


		return static::getBackend()->updateTranslation( $translation, $data );
	}

	/**
	 *
	 * @param string $dictionary
	 * @param Locale $locale
	 * @param bool $force_load (optional, default: false)
	 *
	 * @return Translator_Dictionary
	 */
	public static function loadDictionary( string $dictionary,
	                                       Locale $locale,
	                                       bool   $force_load = false ): Translator_Dictionary
	{
		$dictionary_key = $dictionary . ':' . $locale;

		if(
			!isset( static::$dictionaries[$dictionary_key] ) ||
			$force_load
		) {
			static::$dictionaries[$dictionary_key] = static::getBackend()->loadDictionary( $dictionary, $locale );
		}

		return static::$dictionaries[$dictionary_key];
	}


}