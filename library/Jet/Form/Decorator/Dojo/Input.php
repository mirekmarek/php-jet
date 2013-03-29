<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Decorator_Dojo_Input extends Form_Decorator_Dojo_Abstract {
	/**
	 * @var array
	 */
	protected $decoratable_tags = array(
		"field" => array(
			"get_dojo_type_method_name" => "getDojoType"
		)
	);

	/**
	 * @param string $tag
	 * @param array &$properties
	 *
	 * @return string
	 */
	protected function getDojoType( $tag, array &$properties ) {
		return $this->field->getValidationRegexp() ? "dijit.form.ValidationTextBox" : "dijit.form.TextBox";
	}
}