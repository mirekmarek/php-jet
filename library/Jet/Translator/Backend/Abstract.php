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
abstract class Translator_Backend_Abstract extends Object {
	/**
	 * @var null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null
	 */
	protected static $__factory_method_name = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Translator_Backend_Abstract";

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
	 * Returns dictionary
	 *
	 * @param string $namespace
	 * @param Locale $locale
	 *
	 * @return Translator_Dictionary
	 */
	abstract public function loadDictionary($namespace, Locale $locale);

	/**
	 * Saves dictionary
	 *
	 * @param Translator_Dictionary $dictionary
	 *
	 */
	abstract public function saveDictionary(Translator_Dictionary $dictionary);

	/**
	 * Create backend after installation
	 */
	abstract public function helper_create();

}