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
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/multi-select';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_MULTI_SELECT;

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


		$this->setIsValid();

		return true;
	}


	/**
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty(): bool
	{
		if(
			!$this->_value &&
			$this->is_required
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

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

}