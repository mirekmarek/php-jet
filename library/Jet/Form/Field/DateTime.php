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

class Form_Field_DateTime extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "DateTime";

	/**
	 * @var array
	 */
	protected $_tags_list =  array(
		"field_label",
		"field_error_msg",
		"field",
		"field_time"
	);

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		parent::catchValue($data);

		if($this->_has_value) {

			$name = $this->_name."_time";

			if($data->exists($name)) {
				$this->_value_raw .= " ".$data->getRaw($name);
				$this->_value .= " ".trim( $data->getRaw($name) );
			} else {
				$this->_value_raw = null;
				$this->setValueError("input_missing");
			}
		}

		$this->_value = trim($this->_value);

		if($this->_value) {
			$this->_value = date("c",strtotime($this->_value));
		}
	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
		if(!$this->is_required && $this->_value==="") {
			return true;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if(!@strtotime($this->_value)) {
			$this->setValueError("invalid_format");
			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * @param array $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field($tag_data) {

		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName();
		$properties["id"] = $this->getID();
		$properties["type"] = "text";

		$value = $this->getValue();
		if($value) {
			$properties["value"] = date("Y-m-d", strtotime($value));
		} else {
			$properties["value"] = "";
		}

		return '<input '
			.$this->_getTagPropertiesAsString($properties, "field")
			.'/>';
	}

	/**
	 * @param array $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field_time($tag_data) {

		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName()."_time";
		$properties["id"] = $this->getID()."_time";
		$properties["type"] = "text";
		$properties["value"] = "";

		$value = $this->getValue();

		if($value) {
			$properties["value"] = "T".date("H:i:s", strtotime($value));
		} else {
			$properties["value"] = "";
		}


		return '<input '
			.$this->_getTagPropertiesAsString($properties, "field:time")
			.'/>';
	}

}