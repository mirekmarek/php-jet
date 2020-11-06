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
abstract class DataModel_Definition_Property extends BaseObject implements Form_Field_Definition_Interface
{
	use Form_Field_Definition_Trait;

	/**
	 * @var string
	 */
	protected $data_model_class_name = '';

	/**
	 * @var string
	 */
	protected $type = null;


	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @var string|null
	 */
	protected $related_to_class_name = null;

	/**
	 *
	 * @var string|null
	 */
	protected $related_to_property_name = null;

	/**
	 *
	 * @var string
	 */
	protected $database_column_name = '';

	/**
	 * @var bool
	 */
	protected $is_id = false;

	/**
	 * @var bool
	 */
	protected $is_key = false;

	/**
	 * @var bool
	 */
	protected $is_unique = false;

	/**
	 * @var bool
	 */
	protected $do_not_export = false;

	/**
	 *
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var array
	 */
	protected $backend_options = '';


	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( array $data )
	{

		$i = new static( $data['data_model_class_name'], $data['name'] );

		foreach( $data as $key => $val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

	/**
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param array|null  $definition_data (optional)
	 */
	public function __construct( $data_model_class_name, $name, $definition_data = null )
	{
		$this->data_model_class_name = (string)$data_model_class_name;
		$this->name = $name;

		$this->setUp( $definition_data );

	}

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data )
	{
		if( $definition_data ) {
			unset( $definition_data['type'] );

			foreach( $definition_data as $key => $val ) {
				if( !$this->objectHasProperty( $key ) ) {
					throw new DataModel_Exception(
						$this->data_model_class_name.'::'.$this->name.': unknown definition option \''.$key.'\'  ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}

				$this->{$key} = $val;
			}

			$this->is_id = (bool)$this->is_id;
			$this->is_key = (bool)$this->is_key;
			$this->is_unique = (bool)$this->is_unique;
			$this->form_field_is_required = (bool)$this->form_field_is_required;

			if( $this->is_id ) {
				if( !isset( $definition_data['form_field_type'] ) ) {
					$this->form_field_type = Form::TYPE_HIDDEN;
				}
			}

		}

	}

	/**
	 *
	 * @param string $related_to_class_name
	 * @param string $related_to_property_name
	 *
	 */
	public function setUpRelation( $related_to_class_name, $related_to_property_name )
	{
		$this->related_to_class_name = $related_to_class_name;
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getDataModelClassName()
	{
		return $this->data_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model|DataModel_Definition_Model_Related
	 */
	public function getDataModelDefinition()
	{
		return DataModel_Definition::get( $this->data_model_class_name );
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName()
	{
		return $this->related_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedToPropertyName()
	{
		return $this->related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseColumnName()
	{
		if( !$this->database_column_name ) {
			return $this->getName();
		}

		return $this->database_column_name;
	}

	/**
	 * @return bool
	 */
	public function getIsKey()
	{
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	public function getIsUnique()
	{
		return $this->is_unique;
	}


	/**
	 * @return bool
	 */
	public function doNotExport()
	{
		if($this->is_id && $this->related_to_class_name) {
			return true;
		}

		return $this->do_not_export;
	}

	/**
	 * @return bool
	 */
	public function getMustBeSerializedBeforeStore()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeTableField()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInSelectPartOfQuery()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInInsertRecord()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInUpdateRecord()
	{
		if( $this->getIsId() ) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function getIsId()
	{
		return $this->is_id;
	}

	/**
	 * @return bool
	 */
	public function getCanBeFormField()
	{
		return true;
	}


	/**
	 * @return int|null
	 */
	public function getMaxLen()
	{
		return null;
	}

	/**
	 * @param mixed &$property
	 */
	public function initPropertyDefaultValue( &$property )
	{
		if( $property===null ) {
			$property = $this->getDefaultValue();

			$this->checkValueType( $property );
		}
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->default_value;
	}

	/**
	 * Check data type by definition (retype)
	 *
	 * @param mixed &$value
	 */
	abstract public function checkValueType( &$value );

	/**
	 * @param string $backend_type
	 *
	 * @return array
	 */
	public function getBackendOptions( $backend_type )
	{
		if( !isset( $this->backend_options[$backend_type] ) ) {
			return [];
		}

		return $this->backend_options[$backend_type];
	}

	/**
	 * @param mixed &$property
	 * @param mixed $data
	 *
	 */
	public function loadPropertyValue( &$property, array $data )
	{
		if( !array_key_exists( $this->getName(), $data ) ) {
			return;
		}

		$property = $data[$this->getName()];

		$this->checkValueType( $property );
	}

	/**
	 * @return string
	 */
	public function getFormFieldName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName()
	{
		return $this->data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 *
	 * Example: Locale to string
	 *
	 * @param mixed               &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( &$property )
	{
		return $property;
	}


	/**
	 *
	 * @param array|DataModel_Definition_Property_DataModel[] &$related_definitions
	 *
	 */
	public function getAllRelatedPropertyDefinitions( array &$related_definitions )
	{
	}


}