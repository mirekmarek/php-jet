<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Translator
 * @package Jet
 */
class Translator extends BaseObject {

	const COMMON_NAMESPACE = '_COMMON_';

	/**
	 * @var string
	 */
	protected static $current_namespace = self::COMMON_NAMESPACE;

	/**
	 * @var bool
	 */
	protected static $auto_append_unknown_phrase = JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE;

	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * 
	 * @var Translator_Backend_Abstract
	 */
	protected static $backend;

	/**
	 * @var Translator_Dictionary[]
	 */
	protected static $dictionaries = [];

	/**
	 * @return bool
	 */
	public static function getAutoAppendUnknownPhrase()
	{
		return self::$auto_append_unknown_phrase;
	}

	/**
	 * @param bool $auto_append_unknown_phrase
	 */
	public static function setAutoAppendUnknownPhrase($auto_append_unknown_phrase)
	{
		self::$auto_append_unknown_phrase = $auto_append_unknown_phrase;
	}



	/**
	 * Gets translator backend instance
	 * 
	 * @return Translator_Backend_Abstract
	 */
	public static function getBackend(){
		if(static::$backend === null){
			static::$backend = new Translator_Backend_PHPFiles();

			register_shutdown_function( [get_called_class(), 'saveUpdatedDictionaries']);
		}
		return static::$backend;
	}

	/**
	 * @static
	 *
	 * @param Translator_Backend_Abstract $backend
	 */
	public static function setBackend(Translator_Backend_Abstract $backend ) {
		if(static::$backend === null){
			register_shutdown_function( [get_called_class(), 'saveUpdatedDictionaries']);
		}
		static::$backend = $backend;
	}

	/**
	 * @static
	 *
	 * Shutdown - save all update dictionaries
	 *
	 */
	public static function saveUpdatedDictionaries() {
		$backend = static::getBackend();

		foreach(static::$dictionaries as $dictionary) {
			if($dictionary->getNeedToSave()) {
				$backend->saveDictionary($dictionary);
			}
		}
	}

	/**
	 * Gets translation of given text
	 * 
	 *
	 * @param string $phrase
	 * @param array $data(optional) - data that replace parts of text; input array('KEY1'=>'value1') replaces %KEY1% in text for value1
	 * @param string $namespace(optional)
	 * @param string|Locale $locale(optional) - target locale
	 * @return string
	 */
	public static function getTranslation($phrase, $data= [], $namespace=null, $locale=null ){

		if(!$namespace){
			$namespace = static::$current_namespace;
		}

		if($locale === null){
			$locale = static::$current_locale;
		}

		if(
			!$namespace ||
			!$locale
		) {
			return Data_Text::replaceData($phrase, $data);
		}

		if(is_string($locale)) {
			$locale = new Locale($locale);
		}

		$dictionary = static::loadDictionary($namespace, $locale);

		$translation = $dictionary->getTranslation($phrase, static::getAutoAppendUnknownPhrase() );


		if($data){
			$translation = Data_Text::replaceData($translation, $data);
		}

		return $translation;

	}

	/**
	 * Gets translation of given text
	 *
	 * @param string $text
	 * @param array $data(optional) - data that replace parts of text; input array('KEY1'=>'value1') replaces %KEY1% in text for value1
	 * @param string $namespace(optional)
	 * @param string $locale(optional) - target locale
	 * @return string
	 */
	public static function _($text, $data= [], $namespace=null, $locale=null){
		return static::getTranslation($text, $data, $namespace, $locale);
	}


	/**
	 * @static
	 * @return string
	 */
	public static function getCurrentNamespace() {
		return static::$current_namespace;
	}

	/**
	 * @static
	 * @param string $current_namespace
	 */
	public static function setCurrentNamespace($current_namespace) {
		static::$current_namespace = $current_namespace;
	}

	/**
	 * @static
	 * @return Locale
	 */
	public static function getCurrentLocale() {
		if(!static::$current_locale) {
			static::$current_locale = Mvc::getCurrentLocale();
		}
		return static::$current_locale;
	}

	/**
	 * @static
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale ) {
		static::$current_locale = $current_locale;
	}

	/**
	 * @static
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param bool $force_load (optional, default: false)
	 *
	 * @return Translator_Dictionary
	 */
	public static function loadDictionary($namespace, Locale $locale, $force_load=false) {
		$dictionary_key = $namespace.':'.$locale;

		if(
			!isset(static::$dictionaries[$dictionary_key]) ||
			$force_load
		) {
			static::$dictionaries[$dictionary_key] = static::getBackend()->loadDictionary($namespace, $locale);
		}

		return static::$dictionaries[$dictionary_key];
	}


	/**
	 * Export phrases
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public static function exportDictionary($namespace, Locale $locale ){

		$dictionary = static::loadDictionary( $namespace, $locale );
		return $dictionary->export();
	}

	/**
	 * Import dictionary
	 *
	 * @param string $data
	 *
	 * @throws Translator_Exception
	 *
	 */
	public static function importDictionary($data){

		list($namespace, $locale) = Translator_Dictionary::getImportDataNamespaceAndLocale($data);

		if(!$namespace || !$locale) {
			throw new Translator_Exception(
				'Incorrect file format. Header is missing.',
				Translator_Exception::CODE_IMPORT_INCORRECT_DICTIONARY_EXPORT_FILE_FORMAT
			);
		}

		$dictionary = static::loadDictionary( $namespace, $locale );
		$dictionary->import($data);
		static::getBackend()->saveDictionary($dictionary);
	}

}