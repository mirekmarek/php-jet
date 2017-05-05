<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Translator_Dictionary
 * @package Jet
 */
class Translator_Dictionary extends BaseObject
{
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
	protected $save_required = false;

	/**
	 * @param string $namespace
	 * @param Locale $locale
	 */
	public function __construct( $namespace = '', Locale $locale = null )
	{
		$this->namespace = $namespace;
		$this->locale = $locale;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @return Translator_Dictionary_Phrase[]
	 */
	public function getPhrases()
	{
		return $this->phrases;
	}

	/**
	 * @param      $phrase_txt
	 * @param bool $auto_append_unknown_phrase (optional)
	 *
	 * @return string
	 */
	public function getTranslation( $phrase_txt, $auto_append_unknown_phrase = true )
	{
		$hash = Translator_Dictionary_Phrase::generateHash( $phrase_txt );
		if( isset( $this->phrases[$hash] ) ) {
			return $this->phrases[$hash]->getTranslation();
		}

		$phrase = new Translator_Dictionary_Phrase( $phrase_txt, '', false, $hash );
		if( $auto_append_unknown_phrase ) {
			$this->addPhrase( $phrase );
		}

		return $phrase_txt;
	}

	/**
	 * @param Translator_Dictionary_Phrase $phrase
	 * @param bool                         $save_required
	 *
	 */
	public function addPhrase( Translator_Dictionary_Phrase $phrase, $save_required = true )
	{
		$this->phrases[$phrase->getHash()] = $phrase;
		if( $save_required ) {
			$this->save_required = true;
		}
	}

	/**
	 * @return bool
	 */
	public function saveRequired()
	{
		return $this->save_required;
	}

}