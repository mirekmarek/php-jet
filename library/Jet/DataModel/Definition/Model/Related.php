<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;

/**
 *
 */
abstract class DataModel_Definition_Model_Related extends DataModel_Definition_Model
{


	/**
	 * @var string
	 */
	protected string $main_model_class_name = '';

	/**
	 *
	 * @var array
	 */
	protected array $main_model_relation_id_properties = [];

	/**
	 *
	 * @var bool
	 */
	protected bool $is_sub_related_model = false;

	/**
	 * @var string
	 */
	protected string $parent_model_class = '';

	/**
	 *
	 * @var array
	 */
	protected array $parent_model_relation_id_properties = [];


	/**
	 *
	 */
	public function init(): void
	{
		$this->_initParents();
		$this->_initProperties();
		$this->_initKeys();

		if( !$this->id_properties ) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \'' . $this->getClassName() . '\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents(): void
	{

		$parent_model_class = $this->getClassArgument( 'parent_model_class' );

		if( !$parent_model_class ) {
			throw new DataModel_Exception(
				$this->class_name . ' #[DataModel_Definition(parent_model_class: SomeParent::class)] attribute is not defined ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->parent_model_class = $parent_model_class;

		$main_model_class_name = $parent_model_class;

		$getParent = function( $class_name ): string|null {
			$def = DataModel_Definition::get( $class_name );
			if(
				$def instanceof DataModel_Definition_Model_Main
			) {
				return null;
			}

			return $def->getParentModelClassName();
		};

		while( ($_parent = $getParent( $main_model_class_name )) ) {

			$main_model_class_name = $_parent;

			$this->is_sub_related_model = true;
		}

		if( !is_subclass_of( $main_model_class_name, __NAMESPACE__ . '\DataModel' ) ) {
			throw new DataModel_Exception(
				'Main parent class ' . $main_model_class_name . ' is not subclass of ' . __NAMESPACE__ . '\DataModel ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->main_model_class_name = (string)$main_model_class_name;

	}


	/**
	 *
	 */
	protected function _initProperties(): void
	{

		parent::_initProperties();

		$main_id_relation_defined = [];
		$parent_id_relation_defined = [];

		foreach( $this->properties as $property ) {
			if( !$property->getRelatedToClassName() ) {
				continue;
			}

			if( $property->getRelatedToClassName() == $this->main_model_class_name ) {
				$main_id_relation_defined[] = $property->getRelatedToPropertyName();
			}

			if( $property->getRelatedToClassName() == $this->parent_model_class ) {
				$parent_id_relation_defined[] = $property->getRelatedToPropertyName();
			}
		}

		$related_definition_data = $this->_getPropertiesDefinitionData( $this->main_model_class_name );
		foreach( $related_definition_data as $property_name => $pd ) {
			if( empty( $pd['is_id'] ) ) {
				continue;
			}

			if( !in_array( $property_name, $main_id_relation_defined ) ) {
				throw new DataModel_Exception(
					'Class: \'' . $this->class_name . '\'  Main model relation property is missing! Please declare property with this attribute: #[DataModel_Definition(related_to: \'main.' . $property_name . '\')] ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

		if( $this->is_sub_related_model ) {
			$related_definition_data = $this->_getPropertiesDefinitionData( $this->parent_model_class );

			foreach( $related_definition_data as $property_name => $pd ) {

				if(
					empty( $pd['is_id'] ) ||
					!empty( $pd['related_to'] )
				) {
					continue;
				}

				if( !in_array( $property_name, $parent_id_relation_defined ) ) {
					throw new DataModel_Exception(
						'Class: \'' . $this->class_name . '\'  parent model relation property is missing! Please declare property with this attribute: #[DataModel_Definition(related_to:\'parent.' . $property_name . '\')]',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}

	}

	/**
	 *
	 */
	public function initRelations(): void
	{
		parent::initRelations();

		$main_to_this_join = [];

		$main_model_class_name = $this->getMainModelClassName();


		foreach( $this->properties as $property ) {
			if( $property->getRelatedToClassName() == $main_model_class_name ) {
				$main_to_this_join[$property->getRelatedToPropertyName()] = $property->getName();
			}
		}


		DataModel_Relations::add(
			$main_model_class_name,
			new DataModel_Definition_Relation_Internal(
				$main_model_class_name,
				$this->class_name,
				$main_to_this_join
			)
		);


		if( $this->is_sub_related_model ) {

			$parent_to_this_join = [];

			$parent_model_class = $this->getParentModelClassName();
			$parent_model_definition = $this->getParentModelDefinition();


			foreach( $this->properties as $property ) {
				if( $property->getRelatedToClassName() == $parent_model_class ) {
					$parent_to_this_join[$property->getRelatedToPropertyName()] = $property->getName();
				}

				if( $property->getRelatedToClassName() == $main_model_class_name ) {

					foreach( $parent_model_definition->getProperties() as $parent_property ) {
						if(
							$parent_property->getRelatedToClassName() == $main_model_class_name &&
							$parent_property->getRelatedToPropertyName() == $property->getRelatedToPropertyName()
						) {
							$parent_to_this_join[$parent_property->getName()] = $property->getName();
							break;
						}
					}

				}
			}

			DataModel_Relations::add(
				$parent_model_class,

				new DataModel_Definition_Relation_Internal(
					$parent_model_class,
					$this->class_name,
					$parent_to_this_join
				)
			);
		}


	}


	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getMainModelRelationIdProperties(): array
	{
		$res = [];

		foreach( $this->main_model_relation_id_properties as $property_name ) {
			$res[$property_name] = $this->properties[$property_name];
		}

		return $res;
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getParentModelRelationIdProperties(): array
	{
		$res = [];

		foreach( $this->parent_model_relation_id_properties as $property_name ) {
			$res[$property_name] = $this->properties[$property_name];
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getMainModelClassName(): string
	{
		return $this->main_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getParentModelClassName(): string
	{
		return $this->parent_model_class;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModelDefinition(): DataModel_Definition_Model_Main
	{
		return DataModel_Definition::get( $this->main_model_class_name );
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Related|DataModel_Definition_Model_Main
	 */
	public function getParentModelDefinition(): DataModel_Definition_Model_Related|DataModel_Definition_Model_Main
	{
		return DataModel_Definition::get( $this->parent_model_class );
	}


	/**
	 * @param string $property_name
	 * @param string $related_to
	 * @param array $property_definition_data
	 *
	 * @return DataModel_Definition_Property
	 * @throws DataModel_Exception
	 */
	protected function _initRelationProperty( string $property_name, string $related_to, array $property_definition_data ): DataModel_Definition_Property
	{

		$related_to = explode( '.', $related_to );

		if( count( $related_to ) != 2 ) {
			throw new DataModel_Exception(
				'Invalid #[DataModel_Definition(related_to)] definition format. Examples: #[DataModel_Definition(related_to:\'parent.id\')], #[DataModel_Definition(related_to:\'main.id\')], class:' . $this->class_name,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		[
			$what,
			$related_to_property_name
		] = $related_to;

		if(
			(
				$what != 'parent' &&
				$what != 'main'
			) ||
			!$related_to_property_name
		) {
			throw new DataModel_Exception(
				'Invalid #[DataModel_Definition(related_to)] definition format. Examples: #[DataModel_Definition(related_to:\'parent.id\')], #[DataModel_Definition(related_to:\'main.id\')], class:' . $this->class_name,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		if( !$this->is_sub_related_model && $what == 'parent' ) {
			throw new DataModel_Exception(
				'Invalid #[DataModel_Definition(related_to: \'parent.' . $related_to_property_name . '\')] definition. Use: #[DataModel_Definition(related_to: \'main.' . $related_to_property_name . '\')]  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$getRelatedPropertiesDefinitionData = function( string $class_name ): array {

			$reflection = new ReflectionClass( $class_name );

			$properties_definition_data = Attributes::getClassPropertyDefinition( $reflection, DataModel_Definition::class );

			if( !$properties_definition_data ) {
				throw new DataModel_Exception(
					'DataModel \'' . $this->class_name . '\' does not have any properties defined!',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			return $properties_definition_data;

		};

		$related_to_class_name = '';

		$related_definition_data = [];

		if( $what == 'parent' ) {
			$related_to_class_name = $this->parent_model_class;
			$related_definition_data = $getRelatedPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->parent_model_relation_id_properties;
		}

		if( $what == 'main' ) {
			$related_to_class_name = $this->main_model_class_name;
			$related_definition_data = $getRelatedPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->main_model_relation_id_properties;
		}

		if( !isset( $related_definition_data[$related_to_property_name] ) ) {
			throw new DataModel_Exception(
				'Unknown relation property \'' . $related_to_class_name . '.' . $related_to_property_name . '\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_id_property_data = $related_definition_data[$related_to_property_name];

		$parent_id_property_data['is_key'] = true;
		$parent_id_property_data['is_id'] = true;
		
		$this_id_property = Factory_DataModel::getPropertyDefinitionInstance(
			$this->class_name,
			$property_name,
			$parent_id_property_data
		);

		$this_id_property->setUpRelation( $related_to_class_name, $related_to_property_name );

		$this->properties[$property_name] = $this_id_property;

		$target_properties_array[] = $property_name;

		return $this_id_property;

	}
}