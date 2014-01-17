<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Backend
 */
namespace Jet;

class Translator_Backend_PHPFiles_Config extends Translator_Backend_Config_Abstract {

	/**
	 * @var bool
	 */
	protected static $__config_section_is_obligatory = false;

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		'dictionaries_path' => array(
			'type' => self::TYPE_STRING,
			'is_required' => false,
			'default_value' => '%JET_DATA_PATH%/dictionaries/%TRANSLATOR_NAMESPACE%/%TRANSLATOR_LOCALE%.php',
			'form_field_label' => 'Dictionaries storage path: ',
		)
	);

	/**
	 * @var string
	 */
	protected $dictionaries_path = '';

	/**
	 * @param $namespace
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public function getDictionaryPath( $namespace, Locale $locale ) {

		return Data_Text::replaceSystemConstants( Data_Text::replaceData( $this->dictionaries_path, array(
			'TRANSLATOR_NAMESPACE' => str_replace( '\\','/', $namespace),
			'TRANSLATOR_LOCALE' => (string)$locale
		)));
	}

}