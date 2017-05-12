<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Config_Definition_Property_Int
 * @package Jet
 */
class Config_Definition_Property_Int extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_INT;
	/**
	 * @var int
	 */
	protected $default_value = 0;

	/**
	 * @var int
	 */
	protected $min_value = null;
	/**
	 * @var int
	 */
	protected $max_value = null;


	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		parent::setUp( $definition_data );

		if( $this->min_value!==null ) {
			$this->form_field_min_value = $this->min_value;
		}

		if( $this->max_value!==null ) {
			$this->form_field_max_value = $this->max_value;
		}

		if( $this->form_field_type===null ) {
			$this->form_field_type = Form::TYPE_INT;
		}
	}

	/**
	 * @return int|null
	 */
	public function getMinValue()
	{
		return $this->min_value;
	}

	/**
	 * @param int $min_value
	 */
	public function setMinValue( $min_value )
	{
		$this->min_value = (int)$min_value;
		$this->form_field_min_value = $this->min_value;
	}

	/**
	 * @return int|null
	 */
	public function getMaxValue()
	{
		return $this->max_value;
	}

	/**
	 * @param int $max_value
	 */
	public function setMaxValue( $max_value )
	{
		$this->max_value = (int)$max_value;
		$this->form_field_max_value = $this->max_value;
	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value )
	{
		$value = (int)$value;
	}


	/**
	 * @return string
	 */
	public function getTechnicalDescription()
	{
		$res = 'Type: '.$this->getType().'';

		$res .= ', required: '.( $this->is_required ? 'yes' : 'no' );

		if( $this->default_value ) {
			$res .= ', default value: '.$this->default_value;
		}

		if( $this->min_value ) {
			$res .= ', min. value: '.$this->min_value;
		}

		if( $this->max_value ) {
			$res .= ', max. value: '.$this->max_value;
		}

		if( $this->description ) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

	/**
	 * Column value test - checks range
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateProperties_test_value( &$value )
	{
		if( $this->min_value===null&&$this->max_value===null ) {
			return true;
		}

		if( $this->min_value!==null&&$value<$this->min_value ) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' value '.$value.' is under the minimal value. Minimal value: '.$this->min_value.', current value: '.$value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		if( $this->max_value!==null&&$value>$this->max_value ) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' value is above the maximum value. Maximum value: '.$this->max_value.', current value: '.$value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}

}