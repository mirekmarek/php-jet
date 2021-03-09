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
trait DataModel_Related_MtoN_Trait
{
	use DataModel_Related_Trait;

	/**
	 * @var array
	 */
	protected static array $_load_related_data_where_query_part = [];
	/**
	 * @var array
	 */
	protected static array $_load_related_data_order_by = [];

	/**
	 * @var ?DataModel_IDController
	 */
	private ?DataModel_IDController $_N_id = null;

	/**
	 * @var ?DataModel_Interface
	 */
	private ?DataModel_Interface $_N_instance = null;

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_MtoN
	 */
	public static function dataModelDefinitionFactory( string $data_model_class_name ): DataModel_Definition_Model_Related_MtoN
	{
		$class_name = DataModel_Factory::getModelDefinitionClassNamePrefix() . 'Related_MtoN';

		return new $class_name( $data_model_class_name );
	}


	/**
	 *
	 * @param array $where
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function fetchRelatedData( array $where, DataModel_PropertyFilter $load_filter = null ): array
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 */
		$definition = static::getDataModelDefinition();

		if( $load_filter ) {
			if( !$load_filter->getModelAllowed( $definition->getModelName() ) ) {
				return [];
			}
		}

		$query = new DataModel_Query( $definition );

		$select = DataModel_PropertyFilter::getQuerySelect( $definition, $load_filter );

		$query->setSelect( $select );
		$query->setWhere( $where );


		$order_by = static::getLoadRelatedDataOrderBy();
		if( $order_by ) {
			$query->setOrderBy( $order_by );
		}


		return DataModel_Backend::get( $definition )->fetchAll( $query );
	}

	/**
	 * @return array
	 */
	public static function getLoadRelatedDataOrderBy(): array
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 */
		$definition = static::getDataModelDefinition();

		return static::$_load_related_data_order_by
			? static::$_load_related_data_order_by
			:
			$definition->getDefaultOrderBy();
	}

	/**
	 *
	 * @param array $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel_Related_MtoN_Iterator
	 */
	public static function initRelatedByData( array $this_data, array &$related_data, DataModel_PropertyFilter $load_filter = null ): DataModel_Related_MtoN_Iterator
	{
		$items = [];

		foreach( $this_data as $d ) {
			$items[] = static::initByData( $d, $related_data, $load_filter );
		}

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 * @var DataModel_Related_MtoN_Iterator $iterator
		 */
		$data_model_definition = static::getDataModelDefinition();

		$iterator_class = $data_model_definition->getIteratorClassName();

		$iterator = new $iterator_class( $data_model_definition, $items );

		return $iterator;

	}

	/**
	 * @return DataModel_IDController
	 */
	public function getNId(): DataModel_IDController
	{

		if( !$this->_N_id ) {
			/**
			 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
			 */
			$data_model_definition = static::getDataModelDefinition();

			/**
			 * @var DataModel_Definition_Property[] $n_id_properties
			 * @var DataModel $n_class_name
			 */
			$n_id_properties = $data_model_definition->getNModelRelationIdProperties();
			$n_class_name = $data_model_definition->getNModelClassName();


			$this->_N_id = $n_class_name::getEmptyIDController();

			foreach( $n_id_properties as $n_id_prop_name => $n_id_prop ) {
				$this->_N_id->setValue( $n_id_prop->getRelatedToPropertyName(), $this->{$n_id_prop_name} );
			}

		}

		return $this->_N_id;
	}

	/**
	 * @return null|string|int
	 */
	public function getArrayKeyValue(): null|string|int
	{
		return $this->getNId()->toString();
	}

	/**
	 * @return DataModel_Related_Interface|DataModel_Related_MtoN_Iterator|null
	 */
	public function createNewRelatedDataModelInstance(): DataModel_Related_Interface|DataModel_Related_MtoN_Iterator|null
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$iterator_class = $data_model_definition->getIteratorClassName();

		return new $iterator_class( $data_model_definition );
	}

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy( array $order_by ): void
	{
		static::$_load_related_data_order_by = $order_by;
	}

	/**
	 * @param ?DataModel_PropertyFilter $load_filter
	 *
	 * @return DataModel|null
	 */
	public function getNInstance( DataModel_PropertyFilter $load_filter = null ): DataModel|null
	{

		if( !$this->_N_instance ) {

			/**
			 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
			 */
			$data_model_definition = static::getDataModelDefinition();

			$n_class_name = $data_model_definition->getNModelClassName();

			/** @noinspection PhpUndefinedMethodInspection */
			$this->_N_instance = $n_class_name::load( $this->getNId(), $load_filter );

		}

		return $this->_N_instance;
	}

	/**
	 * @param DataModel_Interface $N_instance
	 */
	public function setNInstance( DataModel_Interface $N_instance ): void
	{
		$this->_N_instance = $N_instance;
		$this->_N_id = $N_instance->getIDController();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$n_id = $N_instance->getIDController();
		$n_id_properties = $data_model_definition->getNModelRelationIdProperties();


		foreach( $n_id_properties as $n_id_property ) {
			/**
			 * @var DataModel_Definition_Property $n_id_property
			 */
			$value = $n_id->getValue( $n_id_property->getRelatedToPropertyName() );

			$this->{$n_id_property->getName()} = $value;
		}

	}


	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null ): array
	{
		return [];
	}


}
