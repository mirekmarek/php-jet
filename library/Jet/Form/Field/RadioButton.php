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
class Form_Field_RadioButton extends Form_Field
{
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'Field/input/RadioButton';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_RADIO_BUTTON;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY         => '',
		self::ERROR_CODE_INVALID_VALUE => '',
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
	public function validate(): bool
	{
		if( $this->_value === null && !$this->is_required ) {
			return true;
		}

		$options = $this->select_options;

		if( !isset( $options[$this->_value] ) ) {
			$this->setError( self::ERROR_CODE_INVALID_VALUE );

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

}