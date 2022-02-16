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
class Translator_Dictionary extends BaseObject
{
	/**
	 * @var Locale|null
	 */
	protected Locale|null $locale = null;

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var Translator_Dictionary_Phrase[]
	 */
	protected array $phrases = [];

	/**
	 * @var bool
	 */
	protected bool $save_required = false;

	/**
	 * @param string $name
	 * @param Locale|null $locale
	 */
	public function __construct( string $name = '', Locale $locale = null )
	{
		$this->setName( $name );
		$this->locale = $locale;
	}

	/**
	 * @param string $name
	 * @throws Translator_Exception
	 */
	protected function setName( string $name ) : void
	{
		if(
			!$name ||
			$name[0]=='.' ||
			str_contains($name, '/') ||
			str_contains($name, '\\')
		) {
			throw new Translator_Exception('Illegal dictionary name ');
		}

		$this->name = $name;
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
	public function getName(): string
	{
		return $this->name;
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
		$hash = Translator::getBackend()->generateHash( $phrase_txt );
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