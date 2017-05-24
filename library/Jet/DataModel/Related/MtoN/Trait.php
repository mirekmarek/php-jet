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
	 * @var DataModel_Id
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
	 * @param DataModel_Id                  $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function loadRelatedData( DataModel_Id $main_id, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 */
		$definition = static::getDataModelDefinition();

		if( $load_filter ) {
			if(
				!$load_filter->getModelAllowed( $definition->getModelName() )  &&
				!$load_filter->getModelAllowed( $definition->getNModelName() )
			) {
				return [];
			}

		}


		$query = static::getLoadRelatedDataQuery( $main_id, $load_filter );

		return DataModel_Backend::get( $definition )->fetchAll( $query );

	}

	/**
	 * @param DataModel_Id                  $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel_Query
	 */
	protected static function getLoadRelatedDataQuery( /** @noinspection PhpUnusedParameterInspection */
		DataModel_Id $main_id, DataModel_PropertyFilter $load_filter = null )
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 */
		$definition = static::getDataModelDefinition();

		$query = new DataModel_Query( $definition );

		$query->setWhere( static::getLoadRelatedDataWhereQueryPart() );

		$where = $query->getWhere();


		$m_id_properties = $definition->getMRelationIdProperties();


		foreach( $m_id_properties as $m_id_property ) {

			/**
			 * @var DataModel_Definition_Property $m_id_property
			 */
			$value = $main_id[$m_id_property->getRelatedToPropertyName()];

			$where->addAND();
			$where->addExpression( $m_id_property, DataModel_Query::O_EQUAL, $value );
		}

		$query->setSelect( $definition->getProperties() );

		$relation = $definition->getRelationToN();
		$this_N_model_name = $definition->getNModelName();
		$query->addRelation( $this_N_model_name, $relation );


		$order_by = static::getLoadRelatedDataOrderBy();
		if( $order_by ) {
			$query->setOrderBy( $order_by );
		}

		return $query;
	}

	/**
	 * @return array
	 */
	public static function getLoadRelatedDataWhereQueryPart()
	{
		return static::$_load_related_data_where_query_part;
	}

	/**
	 * @param array $where
	 */
	public static function setLoadRelatedDataWhereQueryPart( array $where )
	{
		static::$_load_related_data_where_query_part = $where;
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
	 * @param array                         $loaded_related_data
	 * @param DataModel_Id|null             $parent_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function loadRelatedInstances( /** @noinspection PhpUnusedParameterInspection */
		array &$loaded_related_data, DataModel_Id $parent_id = null, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Definition_Model_Related_1toN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();


		$model_name = $data_model_definition->getModelName();
		$items = [];

		if( empty( $loaded_related_data[$model_name] ) ) {
			$loaded_related_data[$model_name] = [];
		}


		foreach( $loaded_related_data[$model_name] as $i => $dat ) {

			/**
			 * @var DataModel_Related_MtoN $loaded_instance
			 */
			$loaded_instance = new static();
			$loaded_instance->setLoadFilter( $load_filter );
			$loaded_instance->setState( $dat );
			$loaded_instance->getNId();

			unset( $loaded_related_data[$model_name][$i] );

			$key = $loaded_instance->getArrayKeyValue();
			if( is_object( $key ) ) {
				$key = (string)$key;
			}

			if( $key!==null ) {
				$items[$key] = $loaded_instance;
			} else {
				$items[] = $loaded_instance;
			}

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
	 * @return DataModel_Id
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
			 */
			$n_id_properties = $data_model_definition->getNRelationIdProperties();
			$n_class_name = $data_model_definition->getNModelClassName();


			/** @noinspection PhpUndefinedMethodInspection */
			$this->_N_id = $n_class_name::getEmptyIdObject();

			foreach( $n_id_properties as $n_id_prop_name => $n_id_prop ) {
				$this->_N_id[$n_id_prop->getRelatedToPropertyName()] = $this->{$n_id_prop_name};
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
		$this->_N_instance = $N_instance->getIdObject();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$n_id = $N_instance->getIdObject();
		$n_id_properties = $data_model_definition->getNRelationIdProperties();


		foreach( $n_id_properties as $n_id_property ) {
			/**
			 * @var DataModel_Definition_Property $n_id_property
			 */
			$value = $n_id[$n_id_property->getRelatedToPropertyName()];

			$this->{$n_id_property->getName()} = $value;
		}

	}

	/**
	 * @return DataModel_Id
	 */
	public function getMId()
	{

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		/**
		 * @var DataModel_Definition_Property[] $m_id_properties
		 */
		$m_id_properties = $data_model_definition->getMRelationIdProperties();
		$m_class_name = $data_model_definition->getMModelClassName();


		/** @noinspection PhpUndefinedMethodInspection */
		$m_id = $m_class_name::getEmptyIdObject();

		foreach( $m_id_properties as $m_id_prop_name => $m_id_prop ) {
			$m_id[$m_id_prop->getRelatedToPropertyName()] = $this->{$m_id_prop_name};
		}

		return $m_id;
	}


	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	public function XMLSerialize( $prefix = '' )
	{

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$N_class_name = $data_model_definition->getNModelClassName();

		$result = '';

		$result .= $prefix.JET_TAB.'<'.$N_class_name.'>'.JET_EOL;
		foreach( $this->getNId() as $id_k => $id_v ) {
			$result .= $prefix.JET_TAB.JET_TAB.'<'.$id_k.'>'.Data_Text::htmlSpecialChars(
					$id_v
				).'</'.$id_k.'>'.JET_EOL;
		}
		$result .= $prefix.JET_TAB.'</'.$N_class_name.'>'.JET_EOL;

		return $result;

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

	/**
	 * @param DataModel_Id $parent_id
	 */
	public function actualizeParentId( DataModel_Id $parent_id )
	{
		$this->setMid( $parent_id );
	}

	/**
	 * @param DataModel_Id $m_id
	 */
	public function setMid( DataModel_Id $m_id )
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		if( $m_id->getDataModelClassName()!=$data_model_definition->getMModelClassName() ) {
			return;
		}

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$m_id_properties = $data_model_definition->getMRelationIdProperties();


		foreach( $m_id_properties as $m_id_property ) {
			/**
			 * @var DataModel_Definition_Property $m_id_property
			 */
			$value = $m_id[$m_id_property->getRelatedToPropertyName()];

			$this->{$m_id_property->getName()} = $value;
		}

	}

	/**
	 * @param DataModel_Id $main_id
	 */
	public function actualizeMainId( DataModel_Id $main_id )
	{
		$this->setMid( $main_id );
	}


}
