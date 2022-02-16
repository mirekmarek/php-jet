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
abstract class Translator_Backend extends BaseObject
{

	/**
	 *
	 */
	public function __construct()
	{
	}

	/**
	 *
	 * @param string $dictionary
	 * @param Locale $locale
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary( string $dictionary, Locale $locale, ?string $file_path = null ): Translator_Dictionary;

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 */
	abstract public function saveDictionary( Translator_Dictionary $dictionary, ?string $file_path = null ): void;


	/**
	 * @param string $translation
	 * @param array $data
	 *
	 * @return string
	 */
	public function updateTranslation( string $translation, array $data ) : string
	{
		if(!$data) {
			return $translation;
		}

		return Data_Text::replaceData( $translation, $data );
	}

	/**
	 *
	 * @param string $phrase
	 *
	 * @return string
	 */
	public function generateHash( string $phrase ): string
	{
		if( strlen( $phrase ) < 255 ) {
			return $phrase;
		}

		return md5( $phrase );
	}
}