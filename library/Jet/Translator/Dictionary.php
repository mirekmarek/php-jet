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
	 * @var array<string,string>
	 */
	protected array $phrases = [];

	/**
	 * @var bool
	 */
	protected bool $save_required = false;

	/**
	 * @param string $name
	 * @param Locale|null $locale
	 * @param array<string,string> $phrases
	 * @
	 */
	public function __construct( string $name = '', ?Locale $locale = null, array $phrases = [] )
	{
		$this->setName( $name );
		$this->locale = $locale;
		$this->phrases = $phrases;
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
	 * @param string $phrase
	 * @return string
	 */
	public function generateHash( string $phrase ): string
	{
		if( strlen( $phrase ) < 255 ) {
			return $phrase;
		}
		
		return md5( $phrase );
	}
	

	/**
	 * @return array<string,string>
	 */
	public function getPhrases(): array
	{
		return $this->phrases;
	}

	/**
	 * @param string $phrase
	 * @param bool $auto_append_unknown_phrase (optional)
	 *
	 * @return string
	 */
	public function getTranslation( string $phrase, bool $auto_append_unknown_phrase = true ): string
	{
		
		$hash = $this->generateHash( $phrase );
		
		if(array_key_exists( $hash, $this->phrases) ) {
			$translation = $this->phrases[$hash];
		} else {
			$translation = '';
			$this->phrases[$hash] = '';
			
			if( $auto_append_unknown_phrase ) {
				$this->save_required = true;
			}
		}
		
		return $translation!=='' ?  $translation : $phrase;
	}

	/**
	 * @param string $phrase
	 * @param string $translation
	 *
	 */
	public function addPhrase( string $phrase, string $translation ): void
	{
		$hash = $this->generateHash( $phrase );
		
		$this->phrases[$hash] = $translation;
		$this->save_required = true;
		
	}
	
	/**
	 * @param string $phrase
	 *
	 */
	public function removePhrase( string $phrase ): void
	{
		$hash = $this->generateHash( $phrase );
		
		if(isset($this->phrases[$hash])) {
			unset($this->phrases[$hash]);
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