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
class Form_Field_WYSIWYG extends Form_Field
{

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_WYSIWYG;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
	];

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getRaw( $this->_name ) );
		} else {
			$this->_value_raw = null;
		}

	}
	
	
	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->validate_required() ||
			!$this->validate_validator()
		) {
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
		$codes = [];

		if( $this->is_required ) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}

		return $codes;
	}
	
	
}