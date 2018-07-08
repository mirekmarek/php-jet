<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition_Property_Bool extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_BOOL;

	/**
	 * @var bool
	 */
	protected $default_value = false;


	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		parent::setUp( $definition_data );

		if( $this->form_field_type===null ) {
			$this->form_field_type = Form::TYPE_CHECKBOX;
		}
	}

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
	{
		$value = (bool)$value;
	}

	/**
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 */
	protected function checkValue( $value )
	{
	}
}