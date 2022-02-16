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
class Form_Field_Select extends Form_Field implements Form_Field_Part_Select_Interface
{
	use Form_Field_Part_Select_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_SELECT;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_VALUE => '',
	];
	
	/**
	 * @return bool
	 */
	protected function validate_required(): bool
	{
		if(
			$this->is_required &&
			$this->_value === '' &&
			array_key_exists('', $this->getSelectOptions())
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
		if( !isset( $this->getSelectOptions()[$this->_value] ) ) {
			$this->setError( Form_Field::ERROR_CODE_INVALID_VALUE );
			
			return false;
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