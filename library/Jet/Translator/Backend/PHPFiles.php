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
class Translator_Backend_PHPFiles extends Translator_Backend
{

	/**
	 * @var ?string
	 */
	protected ?string $dictionaries_base_path = null;

	/**
	 * @var ?string
	 */
	protected ?string $_current_file = null;

	/**
	 * @return string
	 */
	public function getDictionariesBasePath() : string
	{
		if(!$this->dictionaries_base_path) {
			$this->dictionaries_base_path = SysConf_Path::DICTIONARIES();
		}

		return $this->dictionaries_base_path;
	}

	/**
	 * @param string $dictionaries_base_path
	 */
	public function setDictionariesBasePath( string $dictionaries_base_path ) : void
	{
		$this->dictionaries_base_path = $dictionaries_base_path;
	}


	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	public function loadDictionary( string $namespace, Locale $locale, ?string $file_path = null ) : Translator_Dictionary
	{
		if( !$file_path ) {
			$file_path = $this->_getFilePath( $namespace, $locale );
		}

		$dictionary = new Translator_Dictionary( $namespace, $locale );

		if( is_readable( $file_path ) ) {
			/** @noinspection PhpIncludeInspection */
			$data = require $file_path;

			foreach( $data as $phrase => $translation ) {
				$is_translated = ( $translation!=='' );

				$phrase = new Translator_Dictionary_Phrase(
					$phrase, $translation, $is_translated
				);

				$dictionary->addPhrase( $phrase, false );
			}
		}

		return $dictionary;
	}

	/**
	 * @param string $namespace
	 *
	 * @param Locale $locale
	 *
	 * @return string
	 */
	protected function _getFilePath( string $namespace, Locale $locale ) : string
	{

		$namespace = str_replace( '/', '.', $namespace );

		$file = $this->getDictionariesBasePath().$locale.'/'.$namespace.'.php';

		return $file;
	}

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param ?string           $file_path (optional, default: by configuration)
	 */
	public function saveDictionary( Translator_Dictionary $dictionary, ?string $file_path = null ) : void
	{
		if( !$file_path ) {
			$file_path = $this->_getFilePath(
				$dictionary->getNamespace(), $dictionary->getLocale()
			);
		}

		$data = [];
		foreach( $dictionary->getPhrases() as $phrase ) {
			$key = $phrase->getPhrase();
			if( $phrase->getIsTranslated() ) {
				$data[$key] = $phrase->getTranslationRaw();
			} else {
				$data[$key] = '';
			}
		}

		$data = '<?php'.PHP_EOL.'return '.( new Data_Array( $data ) )->export();

		IO_File::write( $file_path, $data );
	}


}