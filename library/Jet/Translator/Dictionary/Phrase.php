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

class Translator_Dictionary_Phrase extends Object {
	/**
	 * @var string
	 */
	protected $phrase = '';

	/**
	 * @var string
	 */
	protected $hash = '';

	/**
	 * @var bool
	 */
	protected $is_translated = false;

	/**
	 * @var string
	 */
	protected $translation = '';

	/**
	 * @param string $phrase
	 * @param string $translation (optional)
	 * @param bool $is_translated (optional)
	 * @param null $hash (optional)
	 */
	public function __construct($phrase, $translation='', $is_translated=false, $hash=null) {
		$this->phrase = $phrase;
		$this->translation = $translation;
		$this->is_translated = (bool)$is_translated;
		if(!$hash) {
			$hash = static::generateHash($phrase);
		}
		$this->hash = $hash;
	}

	/**
	 * @static
	 *
	 * @param $phrase
	 *
	 * @return string
	 */
	public static function generateHash($phrase) {
		return md5($phrase);
	}

	/**
	 * @return string
	 */
	public function getPhrase() {
		return $this->phrase;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * @param boolean $is_translated
	 */
	public function setIsTranslated($is_translated) {
		$this->is_translated = $is_translated;
	}

	/**
	 * @return boolean
	 */
	public function getIsTranslated() {
		return $this->is_translated;
	}

	/**
	 * @param string $translation
	 */
	public function setTranslation($translation) {
		$this->translation = $translation;
	}

	/**
	 * @param bool $only_if_is_translated (optional, default: false)
	 *
	 * @return string
	 */
	public function getTranslation() {
		if( !$this->is_translated ) {
			return $this->phrase;
		}
		return $this->translation;
	}

	/**
	 * @return string
	 */
	public function getTranslationRaw() {
		return $this->translation;
	}


}