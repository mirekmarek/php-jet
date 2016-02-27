<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

class ConfigTestDescendantMock extends ConfigTestMock {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Next string property'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default value'
	 * @JetConfig:form_field_label = 'Next string property:'
	 * @JetConfig:form_field_error_messages = [Form_Field_Input::ERROR_CODE_EMPTY=>'empty']
	 *
	 * @var string
	 */
	protected $next_string_property = '';

	public function getNextStringProperty() {
		return $this->next_string_property;
	}

}