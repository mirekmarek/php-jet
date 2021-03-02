<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $data_model_class_name = '';

	/**
	 * @var string
	 */
	protected string $type = '';


	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 *
	 * @var ?string
	 */
	protected ?string $related_to_class_name = null;

	/**
	 *
	 * @var ?string
	 */
	protected ?string $related_to_property_name = null;

	/**
	 *
	 * @var string
	 */
	protected string $database_column_name = '';

	/**
	 * @var bool
	 */
	protected bool $is_id = false;

	/**
	 * @var bool
	 */
	protected bool $is_key = false;

	/**
	 * @var bool
	 */
	protected bool $is_unique = false;

	/**
	 * @var bool
	 */
	protected bool $do_not_export = false;

	/**
	 *
	 * @var mixed
	 */
	protected $default_value = null;

	/**
	 * @var array
	 */
	protected array $backend_options = [];


	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( array $data ): static
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
	 * @param ?array $definition_data (optional)
	 */
	public function __construct( string $data_model_class_name, string $name, ?array $definition_data = null )
	{
		$this->data_model_class_name = (string)$data_model_class_name;
		$this->name = $name;

		if( $definition_data ) {
			$this->setUp( $definition_data );
		}
	}

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data ): void
	{
		if( $definition_data ) {
			unset( $definition_data['type'] );

			foreach( $definition_data as $key => $val ) {
				if( !$this->objectHasProperty( $key ) ) {
					throw new DataModel_Exception(
						$this->data_model_class_name . '::' . $this->name . ': unknown definition option \'' . $key . '\'  ',
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
	public function setUpRelation( string $related_to_class_name, string $related_to_property_name ): void
	{
		$this->related_to_class_name = $related_to_class_name;
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getDataModelClassName(): string
	{
		return $this->data_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model|DataModel_Definition_Model_Related
	 */
	public function getDataModelDefinition(): DataModel_Definition_Model|DataModel_Definition_Model_Related
	{
		return DataModel_Definition::get( $this->data_model_class_name );
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName(): null|string
	{
		return $this->related_to_class_name;
	}

	/**
	 * @return string|null
	 */
	public function getRelatedToPropertyName(): string|null
	{
		return $this->related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseColumnName(): string
	{
		if( !$this->database_column_name ) {
			return $this->getName();
		}

		return $this->database_column_name;
	}

	/**
	 * @return bool
	 */
	public function getIsKey(): bool
	{
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	public function getIsUnique(): bool
	{
		return $this->is_unique;
	}


	/**
	 * @return bool
	 */
	public function doNotExport(): bool
	{
		if( $this->is_id && $this->related_to_class_name ) {
			return true;
		}

		return $this->do_not_export;
	}

	/**
	 * @return bool
	 */
	public function getMustBeSerializedBeforeStore(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeTableField(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInSelectPartOfQuery(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInInsertRecord(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInUpdateRecord(): bool
	{
		if( $this->getIsId() ) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function getIsId(): bool
	{
		return $this->is_id;
	}

	/**
	 * @return bool
	 */
	public function getCanBeFormField(): bool
	{
		return true;
	}


	/**
	 * @return int|null
	 */
	public function getMaxLen(): int|null
	{
		return null;
	}

	/**
	 * @param mixed &$property
	 */
	public function initPropertyDefaultValue( mixed &$property ): void
	{
		if( $property === null ) {
			$property = $this->getDefaultValue();

			$this->checkValueType( $property );
		}
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue(): mixed
	{
		return $this->default_value;
	}

	/**
	 *
	 * @param mixed &$value
	 */
	abstract public function checkValueType( mixed &$value ): void;

	/**
	 * @param string $backend_type
	 *
	 * @return array
	 */
	public function getBackendOptions( string $backend_type ): array
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
	public function loadPropertyValue( mixed &$property, array $data ): void
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
	public function getFormFieldName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName(): string
	{
		return $this->data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 *
	 *
	 * @param mixed &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( mixed &$property ): mixed
	{
		return $property;
	}


	/**
	 *
	 * @param array|DataModel_Definition_Property_DataModel[] &$related_definitions
	 *
	 */
	public function getAllRelatedPropertyDefinitions( array &$related_definitions ): void
	{
	}


}