<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

trait DataModel_Related_MtoN_Trait {
    use DataModel_Related_Trait;

	/**
	 * @var DataModel_ID_Abstract
	 */
	private $_N_ID;

	/**
	 * @var DataModel
	 */
	private $_N_instance;


	/**
	 * @var array
	 */
	protected static $_load_related_data_where_query_part = [];

	/**
	 * @var array
	 */
	protected static $_load_related_data_order_by = [];

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
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_MtoN
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
    }

    /**
     * @return null
     */
    public function getArrayKeyValue() {
        return $this->getNID()->toString();
    }


	/**
	 * @param DataModel_ID_Abstract $main_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function loadRelatedData(
		DataModel_ID_Abstract $main_ID,
		DataModel_PropertyFilter $load_filter=null
	)
    {

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $definition
	     */
	    $definition = static::getDataModelDefinition();

	    if(
	        $load_filter
	    ) {
		    if(
			    !$load_filter->getModelAllowed( $definition->getModelName() ) &&
			    !$load_filter->getModelAllowed( $definition->getNModelName() )
		    ) {
			    return [];
		    }

	    }


	    $query = static::getLoadRelatedDataQuery( $main_ID, $load_filter );

	    return $definition->getBackendInstance()->fetchAll($query);

    }


	/**
	 * @param DataModel_ID_Abstract $main_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel_Query
	 */
	protected static function getLoadRelatedDataQuery(
		/** @noinspection PhpUnusedParameterInspection */
		DataModel_ID_Abstract $main_ID,
		DataModel_PropertyFilter $load_filter=null
	) {
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $definition
		 */
		$definition = static::getDataModelDefinition();

		$query = new DataModel_Query( $definition );

		$query->setWhere(static::getLoadRelatedDataWhereQueryPart());

		$where = $query->getWhere();


		$M_ID_properties = $definition->getMRelationIDProperties();


		foreach($M_ID_properties as $M_ID_property) {

			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $main_ID[$M_ID_property->getRelatedToPropertyName()];

			$where->addAND();
			$where->addExpression( $M_ID_property, DataModel_Query::O_EQUAL, $value);
		}

		$query->setSelect( $definition->getProperties() );

		$relation = $definition->getRelationToN();
		$this_N_model_name = $definition->getNModelName();
		$query->addRelation($this_N_model_name, $relation);


		$order_by = static::getLoadRelatedDataOrderBy();
		if($order_by) {
			$query->setOrderBy( $order_by );
		}

		return $query;
	}

	/**
	 * @param array $where
	 */
	public static function setLoadRelatedDataWhereQueryPart(array $where)
	{
		static::$_load_related_data_where_query_part = $where;
	}

	/**
	 * @return array
	 */
	public static function getLoadRelatedDataWhereQueryPart()
	{
		return static::$_load_related_data_where_query_part;
	}

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by)
	{
		static::$_load_related_data_order_by = $order_by;
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

		return static::$_load_related_data_order_by ? static::$_load_related_data_order_by : $definition->getDefaultOrderBy();
	}


	/**
	 * @param array $loaded_related_data
	 * @param DataModel_ID_Abstract|null $parent_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
    public static function loadRelatedInstances(
	    /** @noinspection PhpUnusedParameterInspection */
    	array &$loaded_related_data,
	    DataModel_ID_Abstract $parent_ID=null,
	    DataModel_PropertyFilter $load_filter=null
    )
    {

        /**
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = static::getDataModelDefinition();


        $model_name = $data_model_definition->getModelName();
        $items = [];

        if(empty($loaded_related_data[$model_name])) {
            return $items;
        }


        foreach( $loaded_related_data[$model_name] as $i=>$dat ) {

            /**
             * @var DataModel_Related_MtoN $loaded_instance
             */
            $loaded_instance = new static();
	        $loaded_instance->setLoadFilter($load_filter);
            $loaded_instance->setState( $dat );

            unset($loaded_related_data[$model_name][$i]);

            $key = $loaded_instance->getArrayKeyValue();
            if(is_object($key)) {
                $key = (string)$key;
            }

            if($key!==null) {
                $items[$key] = $loaded_instance;
            } else {
                $items[] = $loaded_instance;
            }

        }

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     * @var DataModel_Related_MtoN_Iterator $iterator
	     */
	    $data_model_definition = static::getDataModelDefinition();

	    $iterator_class_name = $data_model_definition->getIteratorClassName();

	    $iterator = new $iterator_class_name( $data_model_definition, $items );

	    return $iterator;

    }


    /**
     * @param DataModel_Interface $N_instance
     */
    public function setNInstance( DataModel_Interface $N_instance ) {
    	$this->_N_instance = $N_instance;
	    $this->_N_instance = $N_instance->getIdObject();

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     */
	    $data_model_definition = static::getDataModelDefinition();

	    $N_ID = $N_instance->getIdObject();
	    $N_ID_properties = $data_model_definition->getNRelationIDProperties();


	    foreach($N_ID_properties as $N_ID_property) {
		    /**
		     * @var DataModel_Definition_Property_Abstract $M_ID_property
		     */
		    $value = $N_ID[$N_ID_property->getRelatedToPropertyName()];

		    $this->{$N_ID_property->getName()} = $value;
	    }

    }

	/**
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel|null
	 */
    public function getNInstance(DataModel_PropertyFilter $load_filter=null ) {

        if(!$this->_N_instance) {

	        /**
	         * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	         */
	        $data_model_definition = static::getDataModelDefinition();

            $n_class_name = $data_model_definition->getNModelClassName();

            /** @noinspection PhpUndefinedMethodInspection */
	        $this->_N_instance = $n_class_name::load($this->getNID(), $load_filter);

        }

        return $this->_N_instance;
    }

    /**
     * @return DataModel_ID_Abstract
     */
    public function getNID() {

        if(!$this->_N_ID) {
            /**
             * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
             */
            $data_model_definition = static::getDataModelDefinition();

            /**
             * @var DataModel_Definition_Property_Abstract[] $N_ID_properties
             */
            $N_ID_properties = $data_model_definition->getNRelationIDProperties();
            $n_class_name = $data_model_definition->getNModelClassName();

            /**
             * @var DataModel $N_model_instance
             */
            $N_model_instance = new $n_class_name();

            $this->_N_ID = $N_model_instance::getEmptyIdObject();

            foreach( $N_ID_properties as $N_ID_prop_name=>$N_ID_prop) {
                $this->_N_ID[$N_ID_prop->getRelatedToPropertyName()] = $this->{$N_ID_prop_name};
            }

        }

        return $this->_N_ID;
    }




    /**
     * @param string $prefix
     *
     * @return string
     */
    public function XMLSerialize($prefix='' ) {

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     */
	    $data_model_definition = static::getDataModelDefinition();

        $N_class_name = $data_model_definition->getNModelClassName();

        $result = '';

        $result .= $prefix . JET_TAB.'<'.$N_class_name.'>'.JET_EOL;
        foreach($this->getNID() as $ID_k=>$ID_v) {
            $result .= $prefix . JET_TAB.JET_TAB.'<'.$ID_k.'>'.Data_Text::htmlSpecialChars($ID_v).'</'.$ID_k.'>'.JET_EOL;
        }
        $result .= $prefix . JET_TAB.'</'.$N_class_name.'>'.JET_EOL;

        return $result;

    }

    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param DataModel_PropertyFilter $property_filter
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields(
        /** @noinspection PhpUnusedParameterInspection */
        DataModel_Definition_Property_Abstract $parent_property_definition,
	    DataModel_PropertyFilter $property_filter=null
    ) {
        return [];
    }

	/**
	 * @param DataModel_ID_Abstract $parent_ID
	 */
	public function actualizeParentID( DataModel_ID_Abstract $parent_ID ) {
		$this->setMid($parent_ID);
	}

	/**
	 * @param DataModel_ID_Abstract $main_ID
	 */
	public function actualizeMainID( DataModel_ID_Abstract $main_ID ) {
		$this->setMid($main_ID);
	}

	/**
	 * @param DataModel_ID_Abstract $M_ID
	 */
	protected function setMid( DataModel_ID_Abstract $M_ID )
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		if($M_ID->getDataModelClassName()!=$data_model_definition->getMModelClassName()) {
			return;
		}

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$M_ID_properties = $data_model_definition->getMRelationIDProperties();


		foreach($M_ID_properties as $M_ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $M_ID[$M_ID_property->getRelatedToPropertyName()];

			$this->{$M_ID_property->getName()} = $value;
		}

	}


}
