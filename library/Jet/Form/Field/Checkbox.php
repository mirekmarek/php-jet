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
class Form_Field_Checkbox extends Form_Field
{

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_CHECKBOX;
	
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY          => '',
		Form_Field::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value_raw = false;
		$this->_value = false;
		$this->_has_value = true;

		if( $data->exists( $this->_name ) ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = $data->getBool( $this->_name );
		}

		$data->set( $this->_name, $this->_value );
	}


	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		if(!$this->validate_validator()) {
			return false;
		}

		$this->setIsValid();
		return true;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		return [];
	}
}