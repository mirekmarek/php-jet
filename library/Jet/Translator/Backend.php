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
abstract class Translator_Backend extends BaseObject
{

	/**
	 *
	 * @param string $dictionary
	 * @param Locale $locale
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary( string $dictionary, Locale $locale, ?string $file_path = null ): Translator_Dictionary;

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param ?string $file_path (optional, default: by configuration)
	 *
	 */
	abstract public function saveDictionary( Translator_Dictionary $dictionary, ?string $file_path = null ): void;
	
	/**
	 * @return Locale[]
	 */
	abstract public function getKnownLocales() : array;
	
	/**
	 * @param Locale $locale
	 * @return array<string,string>
	 */
	abstract public function getKnownDictionaries( Locale $locale ) : array;
	
	abstract public function installApplicationModuleDictionaries( Application_Module_Manifest $module ) : void;
	
	abstract public function collectApplicationModuleDictionaries( Application_Module_Manifest $module ) : void;
	
	abstract public function uninstallApplicationModuleDictionaries( Application_Module_Manifest $module ) : void;
	
}