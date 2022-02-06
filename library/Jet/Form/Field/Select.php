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
class Form_Field_Select extends Form_Field
{
	use Form_Field_Trait_SelectOptions;
	
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_SELECT;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY         => '',
		self::ERROR_CODE_INVALID_VALUE => '',
	];

	/**
	 * @return bool
	 */
	public function validate(): bool
	{

		$options = $this->select_options;

		if(
			$this->is_required &&
			$this->_value === '' &&
			array_key_exists('', $options)
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}


		if( !isset( $options[$this->_value] ) ) {

			$this->setError( self::ERROR_CODE_INVALID_VALUE );

			return false;
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
		return $option_key == $this->getValue();
	}
}