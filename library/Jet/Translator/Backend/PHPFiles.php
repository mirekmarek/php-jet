<?php
/**
 *
 *
 *
 * Translator modules interface
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	 *
	 * @throws IO_File_Exception
	 *
	 * @return Translator_Dictionary
	 */
	public function loadDictionary($namespace, Locale $locale) {
		$dictionary = new Translator_Dictionary( $namespace, $locale );

		$file_path = $this->_getFilePath( $namespace, $locale );

		if(is_readable($file_path)) {
			/** @noinspection PhpIncludeInspection */
			$data = require $file_path;

			foreach($data as $hash=>$phrase_dat) {
				$phrase = new Translator_Dictionary_Phrase(
					$phrase_dat["phrase"],
					$phrase_dat["translation"],
					$phrase_dat["is_translated"],
					$hash
				);
				$dictionary->addPhrase($phrase, false);
			}
		}

		return $dictionary;
	}


	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 *
	 * @throws Translator_Exception
	 * @throws IO_File_Exception
	 */
	public function saveDictionary(Translator_Dictionary $dictionary){
		$file_path = $this->_getFilePath($dictionary->getNamespace(), $dictionary->getLocale());

		$data = array();
		foreach($dictionary->getPhrases() as $phrase) {
			$data[$phrase->getHash()] = array(
				"phrase" => $phrase->getPhrase(),
				"translation" => $phrase->getTranslationRaw(),
				"is_translated" => $phrase->getIsTranslated()
			);
		}

		$data = "<?php\n return ".(new Data_Array($data))->export().";\n";

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

		if($namespace==Translator::COMMON_NAMESPACE) {
			$file = $this->config->getCommonDictionaryPath($locale);
		} else {
			$file = $this->config->getDictionaryPath($namespace, $locale);
		}

		return $file;
	}


	/**
	 * Create backend after installation
	 *
	 */
	public function helper_create() {
	}
}