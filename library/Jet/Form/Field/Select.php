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

class Form_Field_Select extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "Select";

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
	 * @return bool
	 */
	public function validateValue() {
		
		$options = $this->select_options;
		
		if(!isset($options[$this->_value])) {
			$this->setValueError("invalid_value");
			return false;
		}
		
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

		$value = $this->getValue();
		$options = $this->select_options;
				
		$result = "<select {$this->_getTagPropertiesAsString($tag_data)}>\n";


		foreach($options as $val=>$label) {
			$prefix = "";
			if($label instanceof Data_Tree_Node) {
				/**
				 * @var Data_Tree_Node $label
				 */

				$prefix = "";
				$prefix = str_pad( $prefix , $label->getDepth()*2 , " " ,  STR_PAD_LEFT );
				$prefix = str_replace(" ", "&nbsp;", $prefix);
			}


			if($val==$value) {
				$result .= "<option value=\"".htmlspecialchars($val)."\" selected=\"selected\">".$prefix.htmlspecialchars($label)."</option>\n";
			} else {
				$result .= "<option value=\"".htmlspecialchars($val)."\">".$prefix.htmlspecialchars($label)."</option>\n";
			}
		}
		$result .= "</select>\n";

		return $result;
	}
	
}