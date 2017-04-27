<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Translator_Backend_PHPFiles_Config
 *
 * @JetConfig:section_is_obligatory = false
 */
class Translator_Backend_PHPFiles_Config extends Translator_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = JET_TRANSLATOR_DICTIONARIES_PATH
	 * @JetConfig:form_field_label = 'Dictionaries storage path: '
     * @JetConfig:form_field_is_required = true
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify directory path for dictionaries']
	 *
	 * @var string
	 */
	protected $dictionaries_path = JET_TRANSLATOR_DICTIONARIES_PATH;

	/**
	 * @param $namespace
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public function getDictionaryPath( $namespace, Locale $locale ) {

		return Data_Text::replaceSystemConstants( Data_Text::replaceData( $this->dictionaries_path, [
			'TRANSLATOR_NAMESPACE' => str_replace( '\\','/', $namespace),
			'TRANSLATOR_LOCALE' => (string)$locale
		]));
	}

	/**
	 * @param string $dictionaries_path
	 */
	public function setDictionariesPath($dictionaries_path)
	{
		$this->dictionaries_path = $dictionaries_path;
	}

}