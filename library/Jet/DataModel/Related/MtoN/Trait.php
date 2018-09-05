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
trait DataModel_Related_MtoN_Trait
{
	use DataModel_Related_Trait;

	/**
	 * @var array
	 */
	protected static $_load_related_data_where_query_part = [];
	/**
	 * @var array
	 */
	protected static $_load_related_data_order_by = [];

	/**
	 * @var DataModel_IDController
	 */
	private $_N_id;
	/**
	 * @var DataModel
	 */
	private $_N_instance;

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_MtoN
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name )
	{
		return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
	}


	/**
	 *
	 * @param array                  $where
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function fetchRelatedData( array $where, DataModel_PropertyFilter $load_filter = null )
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
	public static function getLoadRelatedDataOrderBy()
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 */
		$definition = static::getDataModelDefinition();

		return static::$_load_related_data_order_by ? static::$_load_related_data_order_by :
			$definition->getDefaultOrderBy();
	}

	/**
	 *
	 * @param array  $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function initRelatedByData( $this_data, array &$related_data, DataModel_PropertyFilter $load_filter = null )
	{
		$items = [];

		foreach( $this_data as $d ) {
			$items[] = static::initByData( $d, $related_data, $load_filter );
		}

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 * @var DataModel_Related_MtoN_Iterator         $iterator
		 */
		$data_model_definition = static::getDataModelDefinition();

		$iterator_class_name = $data_model_definition->getIteratorClassName();

		$iterator = new $iterator_class_name( $data_model_definition, $items );

		return $iterator;

	}

	/**
	 * @return DataModel_IDController
	 */
	public function getNId()
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
				$this->_N_id->setValue( $n_id_prop->getRelatedToPropertyName(), $this->{$n_id_prop_name});
			}

		}

		return $this->_N_id;
	}

	/**
	 * @return null
	 */
	public function getArrayKeyValue()
	{
		return $this->getNId()->toString();
	}

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance()
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$iterator_class_name = $data_model_definition->getIteratorClassName();

		$i = new $iterator_class_name( $data_model_definition );

		return $i;
	}

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy( array $order_by )
	{
		static::$_load_related_data_order_by = $order_by;
	}

	/**
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel|null
	 */
	public function getNInstance( DataModel_PropertyFilter $load_filter = null )
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
	public function setNInstance( DataModel_Interface $N_instance )
	{
		$this->_N_instance = $N_instance;
		$this->_N_instance = $N_instance->getIDController();

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
	 * @param DataModel_PropertyFilter      $property_filter
	 *
	 * @return Form_Field[]
	 */
	public function getRelatedFormFields( /** @noinspection PhpUnusedParameterInspection */
		DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null )
	{
		return [];
	}


}
