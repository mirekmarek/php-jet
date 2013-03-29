<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

class ConfigTestDescendantMock extends ConfigTestMock {
	protected $next_string_property;

	protected static $__config_properties_definition = array(
		"next_string_property" => array(
			"type" => self::TYPE_STRING,
			"description" => "Next string property",
			"is_required" => true,
			"default_value" => "default value",
			"form_field_label" => "Next string property:"
		)
	);

	public function getNextStringProperty() {
		return $this->next_string_property;
	}

}