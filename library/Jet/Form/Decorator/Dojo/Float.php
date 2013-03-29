<?php
/**
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

class Form_Decorator_Dojo_Float extends Form_Decorator_Dojo_Abstract {
	/**
	 * @var array
	 */
	protected $decoratable_tags = array(
		"field" => array(
			"dojo_type" => "dijit.form.NumberTextBox"
		)
	);

	/**
	 * @var Form_Field_Float
	 */
	protected $field;

	/**
	 * @param string $tag
	 * @param array &$properties
	 */
	protected function getDojoProperties( $tag, &$properties ) {
		/*
		if(!empty($properties["rangeMessage"])) {
			$this->_dojo_properties["rangeMessage"] = Tr::_($properties["rangeMessage"]);
			unset($properties["rangeMessage"]);
		} else {
			$this->_dojo_properties["rangeMessage"] = Tr::_($this->field->getErrorMessage("out_of_range"));
		}
*/
		$min = $this->field->getMinValue();
		$max = $this->field->getMaxValue();
		$places = $this->field->getPlaces();

		$constraints = array();
		if($min !== null) $constraints["min"] = $min;
		if($max !== null) $constraints["max"] = $max;
		if($places !== null) $constraints["places"] = $places;

		$this->_dojo_properties["constraints"] = $constraints;

		parent::getDojoProperties($tag, $properties);
	}

}