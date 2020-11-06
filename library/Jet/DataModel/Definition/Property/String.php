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
class DataModel_Definition_Property_String extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $type = DataModel::TYPE_STRING;


	/**
	 * @var int
	 */
	protected $max_len = 255;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_INPUT;

	/**
	 * @param array $definition_data
	 */
	public function setUp( $definition_data )
	{
		if( $definition_data ) {
			parent::setUp( $definition_data );

			$this->max_len = (int)$this->max_len;
		}

	}

	/**
	 * @param mixed &$value
	 *
	 */
	public function checkValueType( &$value )
	{
		$value = (string)$value;
	}

	/**
	 * @return int|null
	 */
	public function getMaxLen()
	{
		return $this->max_len;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType()
	{

		if( $this->form_field_type!=Form::TYPE_INPUT ) {
			return $this->form_field_type;
		}

		if( $this->max_len<=255 ) {
			return Form::TYPE_INPUT;
		} else {
			return Form::TYPE_TEXTAREA;
		}
	}

}