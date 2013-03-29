<?php
/**
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
 * @abstract
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

abstract class Form_Decorator_Dojo_Abstract extends Form_Decorator_Abstract {
	const DOJO_TYPE_PROPERTY = "data-dojo-type";
	const DOJO_PROPS_PROPERTY = "data-dojo-props";

	/**
	 * @var array
	 */
	protected $decoratable_tags = array(
		"field" => array(
			"dojo_type" => "dijit.form.TextBox",
			"get_dojo_type_method_name" => "",
			"get_dojo_props_method_name" => "",

		)
	);

	/**
	 * @var array
	 */
	protected $_dojo_properties = array();

	/**
	 *
	 * @param string $tag
	 * @param array &$properties
	 *
	 * @return void
	 */
	public function decorate( $tag, array &$properties ) {
		if(!isset($this->decoratable_tags[$tag])) {
			return;
		}

		$decorate_data = $this->decoratable_tags[$tag];

		if(!empty($decorate_data["get_dojo_type_method_name"])) {
			$dojo_type = $this->{$decorate_data["get_dojo_type_method_name"]}( $tag, $properties );
		} else {
			$dojo_type = $decorate_data["dojo_type"];
		}

		$get_dojo_type_method_name = "getDojoProperties";
		if(!empty($decorate_data["get_dojo_type_method_name"])) {
			$get_dojo_type_method_name = $decorate_data["get_dojo_type_method_name"];
		}

		$this->$get_dojo_type_method_name($tag, $properties);

		$Dojo = $this->form->getLayout()->requireJavascriptLib("Dojo");
		$Dojo->requireComponent( $dojo_type );


		$_dojo_props = array();
		foreach( $this->_dojo_properties as $k=>$val) {
			$_dojo_props[] = "{$k}:".json_encode($val);
		}

		$properties[static::DOJO_TYPE_PROPERTY] = $dojo_type;
		$properties[static::DOJO_PROPS_PROPERTY] = implode(",", $_dojo_props);
	}

	/**
	 * @param string $tag
	 * @param array &$properties
	 */
	protected function getDojoProperties( $tag, &$properties ) {
		if($this->field->getIsRequired()) {
			$this->_dojo_properties["required"] = "true";
			if(!empty($properties["missingMessage"])) {
				$this->_dojo_properties["missingMessage"] = $properties["missingMessage"];
				unset($properties["missingMessage"]);
			} else {
				$this->_dojo_properties["missingMessage"] = $this->field->getErrorMessage("empty");
			}
		}

		$validation_regexp = $this->field->getValidationRegexp();

		if($validation_regexp){
			$this->_dojo_properties["regExp"] = $validation_regexp;

			if(!empty($properties["invalidMessage"])) {
				$this->_dojo_properties["invalidMessage"] = $properties["invalidMessage"];
				unset($properties["invalidMessage"]);
			} else {
				$this->_dojo_properties["invalidMessage"] = $this->field->getErrorMessage("invalid_format");
			}
		}
	}
}