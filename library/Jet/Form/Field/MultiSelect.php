<?php 
/**
 *
 *
 *
 * specific errors:
 *  invalid_value
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

class Form_Field_MultiSelect extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = 'MultiSelect';

	/**
	 * @var array
	 */
	protected $error_messages = [
				'empty' => 'empty',
				'invalid_format' => 'invalid_format',
				'invalid_value' => 'invalid_value'
	];

	/**
	 * Validates values
	 *
	 * @return bool
	 */
	public function validateValue() {
		$options = $this->select_options;
		if(!$this->_value) {
			$this->_value = [];
		}

		if(!is_array($this->_value)) {
			$this->_value = [$this->_value];
		}

		foreach($this->_value as $item){
			if(!isset($options[$item])) {
				$this->setValueError('invalid_value');
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
			$this->setValueError('empty');
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
					$this->_value = [];
					foreach($this->_value_raw as $item){
						$this->_value[]=$item;
					}
				}			
			}else{
				$this->_value = [$this->_value_raw];
			}
		} else {
			$this->_value_raw = null;
			$this->_value = [];
		}
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName().'[]' );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'multiple', 'multiple' );

		$result = '<select '.$this->_getTagPropertiesAsString( $tag_data ).'>'.JET_EOL;

		$value = $this->_value;

		foreach($this->select_options as $val=>$label) {
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

			$prefix = '';

			if($label instanceof Data_Tree_Node) {
				/**
				 * @var Data_Tree_Node $label
				 */

				$prefix = '';
				$prefix = str_pad( $prefix , $label->getDepth()*2 , ' ' ,  STR_PAD_LEFT );
				$prefix = str_replace(' ', '&nbsp;', $prefix);
			}

			if($selected){
				$result .= '<option value="'.Data_Text::htmlSpecialChars($val).'" selected="selected">'.$prefix.Data_Text::htmlSpecialChars( $label ).'</option>'.JET_EOL;
			}
			else{
				$result .= '<option value="'.Data_Text::htmlSpecialChars($val).'">'.$prefix.Data_Text::htmlSpecialChars( $label ).'</option>'.JET_EOL;
			}
		}
		$result .= '</select>'.JET_EOL;

		return $result;
	}

}