<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_Property_Array
 * @package Jet
 */
class DataModel_Definition_Property_Array extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_ARRAY;

	/**
	 * @var array
	 */
	protected $default_value = [];

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_MULTI_SELECT;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data )
	{
		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );

		if( $this->is_id ) {
			throw new DataModel_Exception(
				'Property '.$this->data_model_class_name.'::'.$this->_name.' is Array and Array can\'t be ID.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

	/**
	 * @return bool
	 */
	public function getMustBeSerializedBeforeStore()
	{
		return true;
	}


	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
	{
		if( !is_array( $value ) ) {
			$value = [ $value ];
		}
	}
}