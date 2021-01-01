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
class DataModel_Definition_Property_CustomData extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_CUSTOM_DATA;

	/**
	 * @var array
	 */
	protected $default_value = [];

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_MULTI_SELECT;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data ) : void
	{
		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );

		if( $this->is_id ) {
			throw new DataModel_Exception(
				'Property '.$this->data_model_class_name.'::'.$this->name.' is Array and Array can\'t be ID.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

	/**
	 * @return bool
	 */
	public function getMustBeSerializedBeforeStore() : bool
	{
		return true;
	}


	/**
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ) : void
	{
		if( !is_array( $value ) ) {
			$value = [ $value ];
		}
	}
}