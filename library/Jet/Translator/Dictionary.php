<?php
/**
 *
 *
 *
 * DataModel handle exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Dictionary
 */
namespace Jet;

class Translator_Dictionary extends BaseObject {
	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * @var Translator_Dictionary_Phrase[]
	 */
	protected $phrases = [];

	/**
	 * @var bool
	 */
	protected $need_to_save = false;

	/**
	 * @param string $namespace
	 * @param Locale $locale
	 */
	public function __construct( $namespace='', Locale $locale=null ) {
		$this->namespace = $namespace;
		$this->locale = $locale;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->namespace;
	}


	/**
	 * @param Translator_Dictionary_Phrase $phrase
	 * @param bool $need_to_save
	 *
	 */
	public function addPhrase( Translator_Dictionary_Phrase $phrase, $need_to_save=true ) {
		$this->phrases[$phrase->getHash()] = $phrase;
		if($need_to_save) {
			$this->need_to_save = true;
		}
	}

	/**
	 * @return Translator_Dictionary_Phrase[]
	 */
	public function getPhrases() {
		return $this->phrases;
	}

	/**
	 * @param $phrase_txt
	 * @param bool $auto_append_unknown_phrase (optional)
	 *
	 * @return string
	 */
	public function getTranslation( $phrase_txt, $auto_append_unknown_phrase=true ) {
		$hash = Translator_Dictionary_Phrase::generateHash($phrase_txt);
		if(isset($this->phrases[$hash])) {
			return $this->phrases[$hash]->getTranslation();
		}

		$phrase = new Translator_Dictionary_Phrase($phrase_txt, '', false, $hash);
		if($auto_append_unknown_phrase) {
			$this->addPhrase($phrase);
		}

		return $phrase_txt;
	}

	/**
	 * @return boolean
	 */
	public function getNeedToSave() {
		return $this->need_to_save;
	}

	/**
	 * Export dictionary
	 *
	 * File format:
	 *
	 * #namespace,locale
	 * phrase;translation
	 * phrase;translation
	 * phrase;translation
	 *
	 *
	 * @return string
	 */
	public function export() {
		$result = '';
		$result .= '#'.$this->namespace.','.$this->locale.JET_EOL;

		foreach($this->phrases as $phrase_i) {
			$phrase = $phrase_i->getPhrase();
			$translation = $phrase_i->getTranslation();

			$phrase = str_replace(';', '\;', $phrase);
			$phrase = str_replace(JET_EOL, '\n', $phrase);
			$phrase = str_replace(JET_TAB, '\t', $phrase);

			$translation = str_replace(';', '\;', $translation);
			$translation = str_replace(JET_EOL, '\n', $translation);
			$translation = str_replace(JET_TAB, '\t', $translation);

			$result .= $phrase.';'.$translation.JET_EOL;
		}

		return $result;

	}

	/**
	 * @param string $data
	 *
	 * @return array|bool
	 */
	public static function getImportDataNamespaceAndLocale($data) {
		$data = explode(JET_EOL, $data);

		foreach($data as $line) {
			$line = trim($line);

			if(!$line) continue;

			if($line[0]=='#') {
				$line = explode(',', substr($line, 1));

				list($namespace, $locale) = $line;

				$locale = new Locale($locale);

				return [$namespace, $locale];
			}

		}

		return [false, false];
	}

	/**
	 * Export dictionary
	 *
	 * File format:
	 *
	 * #namespace,locale
	 * phrase;translation
	 * phrase;translation
	 * phrase;translation
	 *
	 *
	 * @param string $data
	 */
	public function import($data) {
		$data = explode(JET_EOL, $data);

		foreach($data as $line) {
			$line = trim($line);

			if(!$line) continue;

			if($line[0]=='#') {
				continue;
			}

			$separator_position = false;

			for($i=0; $i<strlen($line);$i++) {
				if($line[$i]=='\\') {
					$i++;
					continue;
				}

				if($line[$i]==';') {
					$separator_position=$i;
					break;
				}
			}

			if($separator_position===false) {
				continue;
			}

			$phrase = substr($line, 0, $separator_position);
			$translation = substr($line, $separator_position+1);

			$phrase = str_replace('\;', ';', $phrase);
			$phrase = str_replace('\n', JET_EOL, $phrase);
			$phrase = str_replace('\t', JET_TAB, $phrase);

			$translation = str_replace('\;', ';', $translation);
			$translation = str_replace('\n', JET_EOL, $translation);
			$translation = str_replace('\t', JET_TAB, $translation);

			$translated = true;

			if(!$translation) {
				$translated = false;
			}

			$p = new Translator_Dictionary_Phrase($phrase, $translation, $translated);

			$this->phrases[$p->getHash()] = $p;
		}

	}
}