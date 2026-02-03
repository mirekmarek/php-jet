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
class Form_Field_RadioButton extends Form_Field implements Form_Field_Part_Select_Interface
{
	use Form_Field_Part_Select_Trait;

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_RADIO_BUTTON;
	protected string $_validator_type = Validator::TYPE_OPTION;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
		Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value',
	];
	
	
	/**
	 * @param string $option_key
	 *
	 * @return bool
	 */
	public function optionIsSelected( string $option_key ) : bool
	{
		return $option_key == $this->getValue();
	}
	
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
	
}