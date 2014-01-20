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
	 * @JetC:type = Jet\Config::TYPE_STRING
	 * @JetC:description = 'Next string property'
	 * @JetC:is_required = true
	 * @JetC:default_value = 'default value'
	 * @JetC:form_field_label = 'Next string property:'
	 *
	 * @var string
	 */
	protected $next_string_property = '';

	public function getNextStringProperty() {
		return $this->next_string_property;
	}

}