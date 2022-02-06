<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_MultiSelect extends Form_Field
{
	use Form_Field_Trait_SelectOptions;
	
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_MULTI_SELECT;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY         => '',
		self::ERROR_CODE_INVALID_VALUE => '',
	];

	/**
	 * Validates values
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			$this->is_required &&
			$this->_value === ''
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		$options = $this->select_options;
		if( !$this->_value ) {
			$this->_value = [];
		}

		if( !is_array( $this->_value ) ) {
			$this->_value = [$this->_value];
		}

		foreach( $this->_value as $item ) {
			if( !isset( $options[$item] ) ) {
				$this->setError( self::ERROR_CODE_INVALID_VALUE );

				return false;
			}
		}


		$validator = $this->getValidator();
		if(
			$validator &&
			!$validator( $this )
		) {
			return false;
		}

		$this->setIsValid();
		return true;
	}



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
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_INVALID_VALUE;

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}


		return $codes;
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