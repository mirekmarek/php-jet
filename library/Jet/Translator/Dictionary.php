<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Translator_Dictionary extends BaseObject
{
	/**
	 * @var Locale|null
	 */
	protected Locale|null $locale = null;

	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * @var Translator_Dictionary_Phrase[]
	 */
	protected array $phrases = [];

	/**
	 * @var bool
	 */
	protected bool $save_required = false;

	/**
	 * @param string $namespace
	 * @param Locale|null $locale
	 */
	public function __construct( string $namespace = '', Locale $locale = null )
	{
		$this->namespace = $namespace;
		$this->locale = $locale;
	}

	/**
	 * @return Locale|null
	 */
	public function getLocale(): Locale|null
	{
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @return Translator_Dictionary_Phrase[]
	 */
	public function getPhrases(): array
	{
		return $this->phrases;
	}

	/**
	 * @param string $phrase_txt
	 * @param bool $auto_append_unknown_phrase (optional)
	 *
	 * @return string
	 */
	public function getTranslation( string $phrase_txt, bool $auto_append_unknown_phrase = true ): string
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
	 * @param bool $save_required
	 *
	 */
	public function addPhrase( Translator_Dictionary_Phrase $phrase, bool $save_required = true ): void
	{
		$this->phrases[$phrase->getHash()] = $phrase;
		if( $save_required ) {
			$this->save_required = true;
		}
	}

	/**
	 * @return bool
	 */
	public function saveRequired(): bool
	{
		return $this->save_required;
	}

}