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
class Translator_Backend_Default extends Translator_Backend
{

	/**
	 * @param string $dictionary
	 *
	 * @param Locale $locale
	 *
	 * @return string
	 */
	protected function _getFilePath( string $dictionary, Locale $locale ): string
	{
		return SysConf_Path::getDictionaries() . $locale . '/' . $dictionary . '.php';
	}

	/**
	 *
	 * @param string $dictionary
	 * @param Locale $locale
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	public function loadDictionary( string $dictionary, Locale $locale, ?string $file_path = null ): Translator_Dictionary
	{
		if( !$file_path ) {
			$file_path = $this->_getFilePath( $dictionary, $locale );
		}

		$dictionary = new Translator_Dictionary( $dictionary, $locale );

		if( is_readable( $file_path ) ) {
			$data = require $file_path;

			foreach( $data as $phrase => $translation ) {
				$is_translated = ($translation !== '');

				$phrase = new Translator_Dictionary_Phrase(
					$phrase, $translation, $is_translated
				);

				$dictionary->addPhrase( $phrase, false );
			}
		}

		return $dictionary;
	}

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param ?string $file_path (optional, default: by configuration)
	 */
	public function saveDictionary( Translator_Dictionary $dictionary, ?string $file_path = null ): void
	{
		$data = [];
		foreach( $dictionary->getPhrases() as $phrase ) {
			$key = $phrase->getPhrase();
			if( $phrase->getIsTranslated() ) {
				$data[$key] = $phrase->getTranslationRaw();
			} else {
				$data[$key] = '';
			}
		}

		if( !$file_path ) {
			$file_path = $this->_getFilePath(
				$dictionary->getName(), $dictionary->getLocale()
			);
		}

		IO_File::writeDataAsPhp( $file_path, $data );
	}


}