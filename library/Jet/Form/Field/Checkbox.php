<?php 
/**
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

class Form_Field_Checkbox extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "Checkbox";

	/**
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value_raw = false;
		$this->_value = false;
		$this->_has_value = true;

		if($data->exists($this->_name)) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = $data->getBool($this->_name);
		}
	}

	/**
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		if(!$this->_value && $this->is_required) {
			$this->setValueError("empty");
			return false;	
		}
		
		return true;
	}


	/**
	 * @return bool
	 */
	public function validateValue() {
		$this->_setValueIsValid();
		
		return true;
	}

	/**
	 * @param array $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field( $tag_data ) {
		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName();
		$properties["id"] = $this->getID();
		$properties["type"] = "checkbox";
		$properties["class"] = "checkbox";
		$properties["value"] = 1;
		if($this->getValue()) {
			$properties["checked"] = "checked";
		} else {
			if(isset($properties["checked"])) {
				unset($properties["checked"]);
			}
		}
				
		return '<input '
				.$this->_getTagPropertiesAsString($properties, "field")
				.'/>';
	}
	
}