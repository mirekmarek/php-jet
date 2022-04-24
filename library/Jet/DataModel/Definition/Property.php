<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionObject;

/**
 *
 */
abstract class DataModel_Definition_Property extends BaseObject
{

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
	 * @var array
	 */
	protected array $backend_options = [];

	/**
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param ?array $definition_data (optional)
	 */
	public function __construct( string $data_model_class_name, string $name, ?array $definition_data = null )
	{
		$this->data_model_class_name = $data_model_class_name;
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
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
	 */
	public function getDataModelDefinition(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
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
	 * @return int|null
	 */
	public function getMaxLen(): int|null
	{
		return null;
	}


	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue(): mixed
	{
		$class_name = $this->getDataModelClassName();

		$i = new $class_name();

		$r = new ReflectionObject( $i );
		$p = $r->getProperty( $this->getName() );
		$p->setAccessible(true);

		return $p->getValue($i);
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