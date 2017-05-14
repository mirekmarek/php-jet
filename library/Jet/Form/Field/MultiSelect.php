<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_MultiSelect
 * @package Jet
 */
class Form_Field_MultiSelect extends Form_Field
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
	protected static $default_input_renderer = 'Field/input/MultiSelect';


	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_MULTI_SELECT;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY         => '',
		self::ERROR_CODE_INVALID_VALUE => '',
	];

	/**
	 * Validates values
	 *
	 * @return bool
	 */
	public function validateValue()
	{
		$options = $this->select_options;
		if( !$this->_value ) {
			$this->_value = [];
		}

		if( !is_array( $this->_value ) ) {
			$this->_value = [ $this->_value ];
		}

		foreach( $this->_value as $item ) {
			if( !isset( $options[$item] ) ) {
				$this->setError( self::ERROR_CODE_INVALID_VALUE );

				return false;
			}
		}


		$this->_setValueIsValid();

		return true;
	}


	/**
	 * returns false if value is required and is empty
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty()
	{
		if( !$this->_value&&$this->is_required ) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		return true;
	}


	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data )
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
				$this->_value = [ $this->_value_raw ];
			}
		} else {
			$this->_value_raw = null;
			$this->_value = [];
		}
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