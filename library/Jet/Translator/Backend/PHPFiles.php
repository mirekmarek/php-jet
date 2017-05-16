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
class Translator_Backend_PHPFiles extends Translator_Backend
{

	/**
	 * @var string
	 */
	protected $dictionaries_base_path = JET_PATH_DICTIONARIES;

	/**
	 * @var string
	 */
	protected $_current_file;

	/**
	 * @return string
	 */
	public function getDictionariesBasePath()
	{
		return $this->dictionaries_base_path;
	}

	/**
	 * @param string $dictionaries_base_path
	 */
	public function setDictionariesBasePath( $dictionaries_base_path )
	{
		$this->dictionaries_base_path = $dictionaries_base_path;
	}


	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	public function loadDictionary( $namespace, Locale $locale, $file_path = null )
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
	protected function _getFilePath( $namespace, Locale $locale )
	{

		$namespace = str_replace( '/', '.', $namespace );

		$file = $this->dictionaries_base_path.$locale.'/'.$namespace.'.php';

		return $file;
	}

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param string                $file_path (optional, default: by configuration)
	 */
	public function saveDictionary( Translator_Dictionary $dictionary, $file_path = null )
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

		$data = '<?php'.JET_EOL.'return '.( new Data_Array( $data ) )->export();

		IO_File::write( $file_path, $data );
	}


}