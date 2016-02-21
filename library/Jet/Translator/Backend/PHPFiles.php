<?php
/**
 *
 *
 *
 * Translator modules interface
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Backend
 */
namespace Jet;

class Translator_Backend_PHPFiles extends Translator_Backend_Abstract {
	/**
	 * @var Translator_Backend_PHPFiles_Config
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $_current_file;

	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	public function loadDictionary( $namespace, Locale $locale, $file_path=null ) {
		if(!$file_path) {
			$file_path = $this->_getFilePath( $namespace, $locale );
		}

		$dictionary = new Translator_Dictionary( $namespace, $locale );

		if(is_readable($file_path)) {
			/** @noinspection PhpIncludeInspection */
			$data = require $file_path;

			foreach( $data as $phrase=>$translation ) {
				$is_translated = ($translation!=='');

				$phrase = new Translator_Dictionary_Phrase(
					$phrase,
					$translation,
					$is_translated
				);

				$dictionary->addPhrase($phrase, false);
			}
		}

		return $dictionary;
	}


	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param string $file_path (optional, default: by configuration)
	 */
	public function saveDictionary( Translator_Dictionary $dictionary, $file_path=null ){
		if(!$file_path) {
			$file_path = $this->_getFilePath(
								$dictionary->getNamespace(),
								$dictionary->getLocale()
						);
		}

		$data = [];
		foreach($dictionary->getPhrases() as $phrase) {
			$key = $phrase->getPhrase();
			if($phrase->getIsTranslated()) {
				$data[$key] = $phrase->getTranslationRaw();
			} else {
				$data[$key] = '';
			}
		}

		$data = '<?php'.JET_EOL.'return '.(new Data_Array($data))->export();

		IO_File::write($file_path, $data);
	}


	/**
	 * @param string $namespace
	 *
	 * @param Locale $locale
	 *
	 * @throws Translator_Exception
	 * @return \SQLite3
	 */
	protected function _getFilePath($namespace, Locale $locale) {

		$namespace = str_replace('/', '.', $namespace);

		$file = $this->config->getDictionaryPath($namespace, $locale);

		return $file;
	}


	/**
	 * Create backend after installation
	 *
	 */
	public function helper_create() {
	}
}