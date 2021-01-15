<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
abstract class Translator_Backend extends BaseObject
{

	/**
	 *
	 */
	public function __construct()
	{
	}

	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary( string $namespace, Locale $locale, ?string $file_path = null ): Translator_Dictionary;

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 */
	abstract public function saveDictionary( Translator_Dictionary $dictionary, ?string $file_path = null ): void;


}