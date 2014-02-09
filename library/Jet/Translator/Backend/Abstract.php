<?php
/**
 *
 *
 *
 * Translator modules interface
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Backend
 */

namespace Jet;

/**
 * Class Translator_Backend_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\Translator_Backend_Abstract'
 */
abstract class Translator_Backend_Abstract extends Object {

	/**
	 * @var Translator_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 * @param Translator_Backend_Config_Abstract $config
	 */
	public function __construct( Translator_Backend_Config_Abstract $config ) {
		$this->config = $config;
	}

	/**
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 * @param string $file_path (optional, default: by configuration)
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary($namespace, Locale $locale, $file_path=null );

	/**
	 *
	 * @param Translator_Dictionary $dictionary
	 * @param string $file_path (optional, default: by configuration)
	 *
	 */
	abstract public function saveDictionary(Translator_Dictionary $dictionary, $file_path=null );

	/**
	 * Create backend after installation
	 */
	abstract public function helper_create();

}