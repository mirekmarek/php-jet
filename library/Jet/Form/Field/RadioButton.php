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
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_VALUE => '',
	];


	/**
	 * catch value from input (input = most often $_POST)
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value = null;
		$this->_has_value = true;

		if( $data->exists( $this->_name ) ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getString( $this->_name ) );
		} else {
			$this->_value_raw = null;
			$this->_value = null;
		}
	}
	
	/**
	 * @return bool
	 */
	protected function validate_value(): bool
	{
		if($this->_value) {
			$options = $this->getSelectOptions();
			
			if( !isset( $options[$this->_value] ) ) {
				$this->setError( Form_Field::ERROR_CODE_INVALID_VALUE );
				
				return false;
			}
		}
		
		return true;
	}

	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		
		if(
			!$this->validate_required() ||
			!$this->validate_value() ||
			!$this->validate_validator()
		) {
			return false;
		}
		
		$this->setIsValid();
		return true;
	}

	
	/**
	 * @param string $option_key
	 *
	 * @return bool
	 */
	public function optionIsSelected( string $option_key ) : bool
	{
		return $option_key == $this->getValue();
	}
}