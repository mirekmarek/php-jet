<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Translator extends BaseObject
{

	const COMMON_NAMESPACE = '_COMMON_';

	/**
	 *
	 * @var ?Translator_Backend
	 */
	protected static ?Translator_Backend $backend = null;

	/**
	 * @var string
	 */
	protected static string $current_namespace = self::COMMON_NAMESPACE;

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
			static::$backend = new Translator_Backend_Default();

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
	public static function getCurrentNamespace(): string
	{
		return static::$current_namespace;
	}

	/**
	 *
	 * @param string $current_namespace
	 */
	public static function setCurrentNamespace( string $current_namespace ): void
	{
		static::$current_namespace = $current_namespace;
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
	 * @param string|null $namespace (optional)
	 * @param Locale|null $locale (optional) - target locale
	 *
	 * @return string
	 */
	public static function _( string $text, array $data = [], string|null $namespace = null, Locale|null $locale = null ) : string
	{
		return static::getTranslation( $text, $data, $namespace, $locale );
	}

	/**
	 * Gets translation of given text
	 *
	 *
	 * @param string $phrase
	 * @param array $data (optional) - data that replace parts of text; input array('KEY1'=>'value1') replaces %KEY1% in text for value1
	 * @param string|null $namespace (optional)
	 * @param Locale|null $locale (optional) - target locale
	 *
	 * @return string
	 */
	public static function getTranslation( string $phrase,
	                                       array $data = [],
	                                       string|null $namespace = null,
	                                       Locale|null $locale = null ): string
	{
		if(!trim($phrase)) {
			return $phrase;
		}

		if( !$namespace ) {
			$namespace = static::$current_namespace;
		}

		if( $locale === null ) {
			$locale = static::$current_locale;
		}

		if(
			!$namespace ||
			!$locale
		) {
			return Data_Text::replaceData( $phrase, $data );
		}

		if( is_string( $locale ) ) {
			$locale = new Locale( $locale );
		}

		$dictionary = static::loadDictionary( $namespace, $locale );

		$translation = $dictionary->getTranslation( $phrase, SysConf_Jet_Translator::getAutoAppendUnknownPhrase() );


		if( $data ) {
			$translation = Data_Text::replaceData( $translation, $data );
		}

		return $translation;

	}

	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param bool $force_load (optional, default: false)
	 *
	 * @return Translator_Dictionary
	 */
	public static function loadDictionary( string $namespace,
	                                       Locale $locale,
	                                       bool $force_load = false ): Translator_Dictionary
	{
		$dictionary_key = $namespace . ':' . $locale;

		if(
			!isset( static::$dictionaries[$dictionary_key] ) ||
			$force_load
		) {
			static::$dictionaries[$dictionary_key] = static::getBackend()->loadDictionary( $namespace, $locale );
		}

		return static::$dictionaries[$dictionary_key];
	}


}