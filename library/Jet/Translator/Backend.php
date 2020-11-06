<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @param string|null $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary( $namespace, Locale $locale, $file_path = null );

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param string|null           $file_path (optional, default: by configuration)
	 *
	 */
	abstract public function saveDictionary( Translator_Dictionary $dictionary, $file_path = null );


}