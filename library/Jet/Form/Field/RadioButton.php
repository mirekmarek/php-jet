<?php 
/**
 *
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

class Form_Field_RadioButton extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "RadioButton";

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
	 * @var array
	 */
	protected $_tags_list =  array(
			"field_label",
			"field_error_msg",
			"field_option",
			"field_option_label"
		);


	/**
	 * catch value from input (input = most often $_POST)
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = true;

		if($data->exists($this->_name)) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = trim( $data->getString($this->_name) );
		} else {
			$this->_value_raw = null;
			$this->_value = null;
		}
	}

	/**
	 * @return bool
	 */
	public function validateValue() {
		if($this->_value===null && !$this->is_required) {
			return true;
		}

		$options = $this->select_options;
		
		if(!isset($options[$this->_value])) {
			$this->setValueError("invalid_value");
			return false;
		}
		
		$this->_setValueIsValid();
		
		return true;
	}

	/**
	 * @param array $tag_data
	 *
	 * @return array
	 */
	protected function _generateTag_field_option_label( $tag_data ) {
		
		$options = $this->select_options;
		$result = array();
		
		foreach($tag_data as $td) {
			$tag = "";
			
			$properties = $td["properties"];
			$key = $td["properties"]["key"];
			
			
			if(isset($options[$key])) {
				unset($properties["key"]);
				$properties["for"] = $this->getID()."_{$key}";
				
				$tag = "<label for=\"".$properties["for"]."\">".Tr::_($options[$key])."</label>";
			}
			
			$result[] = array(
						"orig_str" => $td["orig_str"],
						"replacement" => $tag
					);
		}
		
		return $result;
	}

	/**
	 * @param array $tag_data
	 *
	 * @return array
	 */
	protected function _generateTag_field_option( $tag_data ) {
		
		$options = $this->select_options;
		$result = array();
		
		foreach($tag_data as $td) {
			$tag = "";
			
			$properties = $td["properties"];
			$key = $td["properties"]["key"];
			
			
			if(isset($options[$key])) {
				unset($properties["key"]);
				$properties["type"] = "radio";
				$properties["class"] = "radio";
				$properties["name"] = $this->_name;
				$properties["id"] = $this->getID()."_{$key}";
				$properties["value"] = htmlspecialchars($key);
				
				if($this->_value==$key) {
					$properties["checked"] = "checked";
				} else {
					if(isset($properties["checked"]))
						unset($properties["checked"]);
				}
				
				$tag = "<input "
					.$this->_getTagPropertiesAsString($properties, "field:option")
					."/>";
			}
			
			$result[] = array(
						"orig_str" => $td["orig_str"],
						"replacement" => $tag
					);
		}
		
		return $result;
	}

	/**
	 * @param null|string $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {
		if(!$template) {
			$template = $this->_form->getTemplate_field();
		}


		$label = "<jet_form_field_label name=\"{$this->_name}\"/>";
		$field = "<jet_form_field_error_msg name=\"{$this->_name}\" class=\"error\"/>";

		foreach($this->select_options as $key=>$val) {
			$field .= "\t\t\t<jet_form_field_option name=\"{$this->_name}\" key=\"{$key}\"/>\n";
			$field .= "\t\t\t<jet_form_field_option_label name=\"{$this->_name}\" key=\"{$key}\"/><br/>\n";

		}

		return Data_Text::replaceData($template, array(
			"LABEL" => $label,
			"FIELD" => $field
		));

	}	
	
}