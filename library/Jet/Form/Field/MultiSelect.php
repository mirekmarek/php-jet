<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_MultiSelect extends Form_Field implements Form_Field_Part_Select_Interface
{
	use Form_Field_Part_Select_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_MULTI_SELECT;
	protected string $_validator_type = Validator::TYPE_OPTIONS;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRINGS;

	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY         => 'Please enter a value',
		Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value',
	];
		
	
	public function validate() : bool
	{
		$select_options = $this->getSelectOptions();
		
		/**
		 * @var Validator_Option|Validator_Options $validator
		 */
		$validator = $this->getValidator();
		$validator->setValidOptions( array_keys($select_options) );
		
		return parent::validate();
	}
	
	/**
	 * @param string $option_key
	 *
	 * @return bool
	 */
	public function optionIsSelected( string $option_key ) : bool
	{
		$value = $this->getValue();
		
		if(
			is_array( $value ) &&
			!empty( $value )
		) {
			foreach( $value as $val_in ) {
				if( $option_key == (string)$val_in ) {
					return true;
				}
			}
		} else {
			if( $option_key == $value ) {
				return true;
			}
		}
		
		return false;
	}
	
	
	/**
	 * @return string
	 */
	public function getTagNameValue() : string
	{
		return parent::getTagNameValue().'[]';
	}
}