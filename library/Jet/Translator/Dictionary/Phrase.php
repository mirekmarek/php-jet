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
class Translator_Dictionary_Phrase extends BaseObject
{
	/**
	 * @var string
	 */
	protected string $phrase = '';

	/**
	 * @var string
	 */
	protected string $hash = '';

	/**
	 * @var bool
	 */
	protected bool $is_translated = false;

	/**
	 * @var string
	 */
	protected string $translation = '';

	/**
	 * @param string $phrase
	 * @param string $translation (optional)
	 * @param bool $is_translated (optional)
	 * @param string|null $hash (optional)
	 */
	public function __construct( string $phrase, string $translation = '', bool $is_translated = false, string|null $hash = null )
	{
		$this->phrase = $phrase;
		$this->translation = $translation;
		$this->is_translated = $is_translated;
		if( !$hash ) {
			$hash = Translator::getBackend()->generateHash( $phrase );
		}

		$this->hash = (string)$hash;
	}


	/**
	 * @return string
	 */
	public function getPhrase(): string
	{
		return $this->phrase;
	}

	/**
	 * @return string
	 */
	public function getHash(): string
	{
		return $this->hash;
	}

	/**
	 * @return bool
	 */
	public function getIsTranslated(): bool
	{
		return $this->is_translated;
	}

	/**
	 * @param bool $is_translated
	 */
	public function setIsTranslated( bool $is_translated ): void
	{
		$this->is_translated = $is_translated;
	}

	/**
	 *
	 * @return string
	 */
	public function getTranslation(): string
	{
		if( !$this->is_translated ) {
			return $this->phrase;
		}

		return $this->translation;
	}

	/**
	 * @param string $translation
	 */
	public function setTranslation( string $translation ): void
	{
		$this->translation = $translation;
	}

	/**
	 * @return string
	 */
	public function getTranslationRaw(): string
	{
		return $this->translation;
	}


}