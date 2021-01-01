<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition_Property_Array extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_ARRAY;

	/**
	 * @var array
	 */
	protected $default_value = [];


	/**
	 * @param ?array $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( ?array $definition_data = null ) : void
	{
		parent::setUp( $definition_data );

		if( $this->form_field_type===null ) {
			$this->form_field_type = Form::TYPE_MULTI_SELECT;
		}
	}

	/**
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ) : void
	{
		if( !is_array( $value ) ) {
			$value = [];
		}
	}

	/**
	 *
	 * @param mixed $value
	 */
	protected function checkValue( mixed $value ) : void
	{
	}
}