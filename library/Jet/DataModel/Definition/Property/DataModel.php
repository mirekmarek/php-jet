<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Definition_Property_DataModel extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_DATA_MODEL;

	/**
	 * @var ?string
	 */
	protected ?string $data_model_class = null;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data ): void
	{

		if( $definition_data ) {
			parent::setUp( $definition_data );

			if( $this->is_id ) {
				throw new DataModel_Exception(
					$this->data_model_class_name . '::' . $this->name . ' property type is DataModel. Can\'t be ID! ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}


			if( !$this->data_model_class ) {
				throw new DataModel_Exception(
					'Property ' . $this->data_model_class_name . '::' . $this->name . ' is DataModel, but data_model_class is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}


	/**
	 *
	 * @return string
	 */
	public function getValueDataModelClass() : string
	{
		return $this->data_model_class;
	}

	/**
	 *
	 * @param mixed               &$property
	 *
	 * @return array|null
	 */
	public function getJsonSerializeValue( mixed &$property ): ?array
	{
		if( !$property ) {
			return null;
		}

		if(is_object($property)) {
			return $property->jsonSerialize();
		}

		if(is_array($property)) {
			$res = [];

			foreach($property as $v) {
				/**
				 * @var DataModel $v
				 */
				$res[] = $v->jsonSerialize();
			}

			return $res;
		}


		return null;
	}

	/**
	 * @return bool
	 */
	public function getCanBeTableField(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInSelectPartOfQuery(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInInsertRecord(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInUpdateRecord(): bool
	{
		return false;
	}

	/**
	 * @param mixed &$property
	 * @param array $data
	 *
	 */
	public function loadPropertyValue( mixed &$property, array $data ): void
	{
	}

	/**
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function checkValueType( mixed &$value ): void
	{
		throw new DataModel_Exception(
			'You can not use checkValueType for the property that is DataObject (property: ' . $this->name . ')'
		);
	}

	/**
	 *
	 * @param array|DataModel_Definition_Property_DataModel[] &$related_definitions
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function getAllRelatedPropertyDefinitions( array &$related_definitions ): void
	{
		/**
		 * @var DataModel_Definition_Property_DataModel[] $related_definitions
		 */

		$related_model_definition = $this->getValueDataModelDefinition();

		$related_model_name = $related_model_definition->getModelName();


		if( isset( $related_definitions[$related_model_name] ) ) {
			$prev = $related_definitions[$related_model_name]->getValueDataModelClass();
			$current = $this->getValueDataModelClass();

			throw new DataModel_Exception(
				'Data model name ('.$related_model_name.') collision: ' . $prev . ' vs ' . $current, DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		$related_definitions[$related_model_name] = $this;

		foreach( $related_model_definition->getProperties() as $related_property_definition ) {
			$related_property_definition->getAllRelatedPropertyDefinitions( $related_definitions );
		}


	}

	/**
	 * @return DataModel_Definition_Model_Related
	 */
	public function getValueDataModelDefinition() : DataModel_Definition_Model_Related
	{
		/**
		 * @var DataModel_Definition_Model_Related $definition
		 */

		$definition = DataModel::getDataModelDefinition( $this->getValueDataModelClass() );

		return $definition;
	}
}