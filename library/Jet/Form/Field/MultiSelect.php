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

	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY         => '',
		Form_Field::ERROR_CODE_INVALID_VALUE => '',
	];
		
	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value = null;
		$this->_has_value = true;
		
		if( $data->exists( $this->_name ) ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			
			if( is_array( $this->_value_raw ) ) {
				if( !empty( $this->_value_raw ) ) {
					$this->_value = [];
					foreach( $this->_value_raw as $item ) {
						$this->_value[] = $item;
					}
				}
			} else {
				$this->_value = [$this->_value_raw];
			}
		} else {
			$this->_value_raw = null;
			$this->_value = [];
		}
	}
	
	
	/**
	 * @return bool
	 */
	protected function validate_required(): bool
	{
		if(
			$this->is_required &&
			!$this->_value
		) {
			$this->setError( Form_Field::ERROR_CODE_EMPTY );
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	protected function validate_value(): bool
	{
		$options = $this->getSelectOptions();
		
		foreach( $this->_value as $item ) {
			if( !isset( $options[$item] ) ) {
				$this->setError( Form_Field::ERROR_CODE_INVALID_VALUE );
				
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
	 * Validates values
	 *
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