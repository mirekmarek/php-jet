<?php 
/**
 *
 *
 *
 * specific errors:
 *  invalid_value
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

class Form_Field_MultiSelect extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "MultiSelect";

	/**
	 * @var array
	 */
	protected $error_messages = array(
				"input_missing" => "input_missing",
				"empty" => "empty",
				"invalid_format" => "invalid_format",
				"invalid_value" => "invalid_value"
			);

	/**
	 * Validates values
	 *
	 * @return bool
	 */
	public function validateValue() {
		$options = $this->select_options;
		if(!$this->_value) {
			$this->_value = array();
		}

		if(!is_array($this->_value)) {
			$this->_value = array( $this->_value );
		}

		foreach($this->_value as $item){
			if(!isset($options[$item])) {
				$this->setValueError("invalid_value");
				return false;
			}
		}

		
		$this->_setValueIsValid();

		return true;
	}


	/**
	 * returns false if value is required and is empty
	 *
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
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = true;

		if( $data->exists($this->_name) ) {
			$this->_value_raw = $data->getRaw( $this->_name );

			if(is_array($this->_value_raw)){
				if(!empty($this->_value_raw)){
					$this->_value = array();
					foreach($this->_value_raw as $item){
						$this->_value[]=$item;
					}
				}			
			}else{
				$this->_value = array( $this->_value_raw );
			}
		} else {
			$this->_value_raw = null;
			$this->_value = array();
		}
	}

	/**
	 * @param array $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field($tag_data) {

		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName()."[]";
		$properties["id"] = $this->getID();
		$properties["multiple"] = "multiple";

		$value = $this->_value;

		$options = $this->select_options;

		$result = "<select "
			.$this->_getTagPropertiesAsString($properties, "field")
			.">\n";

		foreach($options as $val=>$label) {
			$selected = false;

			if(is_array($value) && !empty($value)){
				foreach($value as $valIn){
					if((string)$val == (string)$valIn){
						$selected = true;
						continue;
					}
				}
			}
			else{
				if($val==$value) {
					$selected = true;
				}
			}

			$prefix = "";

			if($label instanceof Data_Tree_Node) {
				/**
				 * @var Data_Tree_Node $label
				 */

				$prefix = "";
				$prefix = str_pad( $prefix , $label->getDepth()*2 , " " ,  STR_PAD_LEFT );
				$prefix = str_replace(" ", "&nbsp;", $prefix);
			}

			if($selected){
				$result .= "<option value=\"".htmlspecialchars($val)."\" selected=\"selected\">".$prefix.htmlspecialchars( $label )."</option>\n";
			}
			else{
				$result .= "<option value=\"".htmlspecialchars($val)."\">".$prefix.htmlspecialchars( $label )."</option>\n";
			}
		}
		$result .= "</select>\n";

		return $result;
	}

}