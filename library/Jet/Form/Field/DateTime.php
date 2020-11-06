<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Form_Field_DateTime extends Form_Field_Input
{
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
	protected static $default_input_renderer = 'Field/input/DateTime';


	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_DATE_TIME;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data )
	{
		parent::catchInput( $data );

		if( $this->_value==='' ) {
			$this->_value = null;
		}

	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate()
	{

		if( $this->_value ) {

			$check = \DateTime::createFromFormat( 'Y-m-d\TH:i', $this->_value );
			$check_c = \DateTime::createFromFormat( 'Y-m-d\TH:i:s', $this->_value );

			if( !$check && !$check_c ) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );

				return false;
			}
		} else {
			if($this->is_required) {
				$this->setError( self::ERROR_CODE_EMPTY );
				return false;
			}
		}

		$this->setIsValid();

		return true;
	}

	/**
	 * returns field value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}

}