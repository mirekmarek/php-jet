<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static $default_input_renderer = 'Field/input/RadioButton';


	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_RADIO_BUTTON;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY         => '',
		self::ERROR_CODE_INVALID_VALUE => '',
	];


	/**
	 * catch value from input (input = most often $_POST)
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data )
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
	public function validate()
	{
		if( $this->_value===null && !$this->is_required ) {
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
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_INVALID_VALUE;

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}


		return $codes;
	}

}