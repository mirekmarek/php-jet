<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Decorator_Dojo_Int extends Form_Decorator_Dojo_Abstract {
	/**
	 * @var array
	 */
	protected $decoratable_tags = array(
		"field" => array(
			"dojo_type" => "dijit.form.NumberTextBox"
		)
	);

	/**
	 * @var Form_Field_Int
	 */
	protected $field;

	/**
	 * @param Form_Parser_TagData $tag_data
	 */
	protected function getDojoProperties( Form_Parser_TagData $tag_data ) {

		if(!empty($properties["rangeMessage"])) {
			$this->_dojo_properties["rangeMessage"] = Tr::_($properties["rangeMessage"]);
			unset($properties["rangeMessage"]);
		} else {
			$this->_dojo_properties["rangeMessage"] = Tr::_($this->field->getErrorMessage("out_of_range"));
		}

		$min = $this->field->getMinValue();
		$max = $this->field->getMaxValue();

		$constraints = array();
		if($min !== null) $constraints["min"] = $min;
		if($max !== null) $constraints["max"] = $max;
		$constraints["places"] = 0;

		$this->_dojo_properties["constraints"] = $constraints;

		parent::getDojoProperties($tag_data);
	}

}