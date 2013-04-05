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
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( "name", $this->getName() );
		$tag_data->setProperty( "id", $this->getID() );
		$tag_data->setProperty( "type", "checkbox" );
		$tag_data->setProperty( "value", 1);


		if($this->getValue()) {
			$tag_data->setProperty("checked", "checked");
		} else {
			$tag_data->unsetProperty("checked");
		}

		return "<input {$this->_getTagPropertiesAsString($tag_data)}/>";

	}
	
}