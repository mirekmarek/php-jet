<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query extends Object {
	const L_O_OR = 'OR';
	const L_O_AND = 'AND';

	const O_EQUAL = '=';
	const O_NOT_EQUAL = '!=';
	const O_LIKE = '*';
	const O_NOT_LIKE = '!*';
    const O_GREATER_THAN = '>';
    const O_LESS_THAN = '<';
    const O_GREATER_THAN_OR_EQUAL = '>=';
    const O_LESS_THAN_OR_EQUAL = '<=';


	const JOIN_TYPE_LEFT_JOIN = 'LEFT_JOIN';
	const JOIN_TYPE_LEFT_OUTER_JOIN = 'LEFT_OUTER_JOIN';

	/**
	 * @var array
	 */
	public static $_available_operators = array(
		self::O_NOT_EQUAL,
		self::O_NOT_LIKE,
        self::O_GREATER_THAN_OR_EQUAL,
        self::O_LESS_THAN_OR_EQUAL,
		self::O_EQUAL,
		self::O_LIKE,
        self::O_GREATER_THAN,
        self::O_LESS_THAN,

	);

	/**
	 * @var string
	 */
	protected $main_data_model_class_name;

	/**
	 * @var DataModel
	 */
	protected $main_data_model;

	/**
	 * The array key is related data model name
	 *
	 * @var DataModel_Query_Relation_Abstract[]
	 */
	protected $relations = array();


	/**
	 * @var DataModel_Query_Select
	 */
	protected $select;

	/**
	 * @var DataModel_Query_Where
	 */
	protected $where;

	/**
	 * @var DataModel_Query_Having
	 */
	protected $having;


	/**
	 * Order by columns (items definition or custom columns defined load_properties)
	 *
	 * @var DataModel_Query_OrderBy_Item[]
	 */
	protected $order_by;


	/**
	 * Group by columns
	 *
	 * @var DataModel_Query_GroupBy
	 */
	protected $group_by;

	/**
	 * Offset value
	 *
	 * @var int
	 */
	protected $offset = null;

	/**
	 * Limit value
	 *
	 * @var int
	 */
	protected $limit = null;


	/**
	 * @var DataModel[]
	 */
	protected static $_classes_instance = array();

	/**
	 * @param DataModel $main_data_model
	 */
	public function __construct( DataModel $main_data_model ) {
		$this->main_data_model = $main_data_model;
		$this->main_data_model_class_name = $this->main_data_model->getDataModelDefinition()->getClassName();
	}


	/**
	 *
	 * @param DataModel $main_data_model
	 * @param array $where
	 *
	 * @throws DataModel_Query_Exception
	 * @return DataModel_Query
	 */
	public static function createQuery( DataModel $main_data_model, array $where=array() ) {

		$result = new self( $main_data_model );
		if($where) {
			$result->setWhere($where);
		}

		return $result;
	}


	/**
	 * @return DataModel
	 */
	public function getMainDataModel() {
		return $this->main_data_model;
	}

	/**
	 * @param DataModel $data_model
	 */
	public function setMainDataModel( DataModel $data_model) {
		$this->main_data_model = $data_model;
		$this->main_data_model_class_name = $this->main_data_model->getDataModelDefinition()->getClassName();
	}

	/**
	 *
	 * @param array $items
	 *
	 * @return \Jet\DataModel_Query
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function setSelect( array $items ) {
		$this->select = new DataModel_Query_Select($this, $items);

		return $this;
	}

	/**
	 * @return DataModel_Query_Select
	 */
	public function getSelect() {
		return $this->select;
	}

	/**
	 * @param array $where
	 *
	 * @return \Jet\DataModel_Query
	 */
	public function setWhere( array $where ) {
		$this->where = new DataModel_Query_Where( $this, $where );

		return $this;
	}

	/**
	 * @return DataModel_Query_Where
	 */
	public function getWhere() {
		return $this->where;
	}


	/**
	 * @param array $having
	 *
	 * @return \Jet\DataModel_Query
	 */
	public function setHaving( array $having ) {
		$this->having = new DataModel_Query_Having( $this, $having );

		return $this;
	}

	/**
	 * @return DataModel_Query_Having
	 */
	public function getHaving() {
		return $this->having;
	}


	/**
	 * Sets group by columns
	 *
	 * @param string[]|string $group_by
	 *
	 * @return \Jet\DataModel_Query
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function setGroupBy( $group_by ) {
		$this->group_by = new DataModel_Query_GroupBy( $this, $group_by );

		return $this;
	}


	/**
	 * @return DataModel_Query_GroupBy
	 */
	public function getGroupBy() {
		return $this->group_by;
	}

	/**
	 * Sets order by columns
	 *
	 * @param string[]|string $order_by
	 *
	 * @return \Jet\DataModel_Query
	 *
	 * @throws DataModel_Query_Exception
	 *
	 */
	public function setOrderBy( $order_by ) {
		$this->order_by = new DataModel_Query_OrderBy($this, $order_by);

		return $this;
	}

	/**
	 * @return DataModel_Query_OrderBy
	 */
	public function getOrderBy() {
		return $this->order_by;
	}


	/**
	 * Sets limit (and offset)
	 *
	 * @param int $limit
	 * @param int $offset (optional)
	 *
	 * @return \Jet\DataModel_Query
	 */
	public function setLimit( $limit, $offset=null ) {
		$this->limit = (int)$limit;
		$this->offset = $offset===null ? null : (int)$offset;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @return int|null
	 */
	public function getOffset() {
		return $this->offset;
	}


	/**
	 * @return DataModel_Query_Relation_Abstract[]
	 */
	public function getRelations() {
		return $this->relations;
	}

	/**
	 * @param string $related_data_model_or_outer_relation_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Query_Relation_Abstract
	 */
	public function getRelation( $related_data_model_or_outer_relation_name ) {
		if(!isset($this->relations[$related_data_model_or_outer_relation_name])) {
			throw new DataModel_Query_Exception(
				'Unknown relation to class \''.$this->main_data_model_class_name.'\' <-> \''.$related_data_model_or_outer_relation_name.'\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $this->relations[$related_data_model_or_outer_relation_name];
	}

	/**
	 * Alias of query->getRelation('relation_name')->setJoinType(join_type);
	 *
	 * @param $related_data_model_outer_relation_name
	 * @param $join_type
	 *
	 * @return DataModel_Query
	 */
	public function setRelationJoinType( $related_data_model_outer_relation_name, $join_type ) {
		$this->getRelation($related_data_model_outer_relation_name)->setJoinType($join_type);

		return $this;
	}

	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------

	/**
	 *
	 * @param string $class_name
	 * @return DataModel
	 */
	protected static function _getClassInstance( $class_name ) {

		$class_name = trim($class_name);

		if( !isset(self::$_classes_instance[$class_name]) ) {
			self::$_classes_instance[$class_name] = Factory::getInstance($class_name);
		}

		return self::$_classes_instance[$class_name];
	}


	/**
	 * Property_name examples:
	 *
	 * this.property_name
	 * this.related_property.property_name
	 * this.related_property.next_related_property.property_name
	 *
	 * outer_relation_name.property_name
	 *
	 * M2N_related_class_name.property_name
	 * M2N_related_class_name.related_property.property_name
	 * M2N_related_class_name.related_property.next_related_property.property_name
	 *
	 *
	 * @param string $property_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function _getPropertyAndSetRelation( $property_name ) {
		$property_name_parts = explode('.', $property_name);

		if(count($property_name_parts)<2) {
			throw new DataModel_Query_Exception(
				'Invalid property name: \''.$property_name.'\'. Valid examples: this.property_name, this.related_property.property_name, this.related_property.next_related_property.property_name, property_name_M2N_relation.property_name, ...',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$part = array_shift($property_name_parts);

		/**
		 * @var DataModel_Query_Relation_Outer|null
		 */
		$outer_relation = null;

		if($part=='this') {
			$data_model = $this->main_data_model;
			$is_main_do = true;
		} else {
			//Outer relations ....
			$main_data_model_outer_relations = $this->main_data_model->getDataModelOuterRelationsDefinition();

			if(isset($main_data_model_outer_relations[$part])) {

				$outer_relation =  $main_data_model_outer_relations[$part];

				$outer_relation_name = $outer_relation->getName();
				if(!isset($this->relations[$outer_relation_name])) {
					$this->relations[$outer_relation_name] = $outer_relation;
				}

				$data_model = $outer_relation->getRelatedDataModelInstance();
			} else {
				//M2N relations ...
				$main_data_model_properties = $this->main_data_model->getDataModelDefinition()->getProperties();
				if( !isset($main_data_model_properties[$part]) ) {
					throw new DataModel_Query_Exception(
						'Unknown property \''.$part.'\'',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
				}

				/**
				 * @var DataModel_Definition_Property_DataModel $property_definition
				 */
				$property_definition = $main_data_model_properties[$part];
				if(!$main_data_model_properties[$part]->getIsDataModel()) {
					throw new DataModel_Query_Exception(
						'Property \''.$part.'\' is not DataModel!  Is not it more like this.'.$part.' ?',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
				}

				$data_model = static::_getClassInstance($property_definition->getDataModelClass());
				if( !($data_model instanceof DataModel_Related_MtoN) ) {
					throw new DataModel_Query_Exception(
						'Property \''.$part.'\' is not M2N relation. Is not it more like this.'.$part.' ?',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);

				}

				$data_model = static::_getClassInstance($data_model->getNModelClassName());
			}


			$is_main_do = false;
		}

		$property = null;
		while($property_name_parts) {
			$part = array_shift($property_name_parts);
			$properties = $data_model->getDataModelDefinition()->getProperties();

			if( !isset($properties[$part]) ) {
				throw new DataModel_Query_Exception(
					'Unknown property: \''.$property_name.'\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}

			$property = $properties[$part];

			if($property->getIsDataModel()) {
				/**
				 * @var DataModel_Definition_Property_DataModel $property
				 */
				$data_model = static::_getClassInstance($property->getDataModelClass());
				$is_main_do = false;
				unset($property);
				$property = null;
			}
		}

		if(!$property) {
			throw new DataModel_Query_Exception(
				'Unknown property: \''.$property_name.'\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		if(!$is_main_do && !$outer_relation) {
			$this->_addRelatedModel( $data_model->getDataModelDefinition() );
		}

		return $property;
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $related_model_definition
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _addRelatedModel( DataModel_Definition_Model_Abstract $related_model_definition ) {
		$main_class_name = $this->main_data_model_class_name;
		$related_class_name = $related_model_definition->getClassName();
		$related_model_name = $related_model_definition->getModelName();

		if( $related_class_name == $main_class_name ) {
			return;
		}

		if( array_key_exists($related_model_definition->getModelName(), $this->relations) ) {
			return;
		}

		if(
			$related_model_definition instanceof DataModel_Definition_Model_Related_1to1 ||
			$related_model_definition instanceof DataModel_Definition_Model_Related_1toN
		) {
			/**
			 * @var DataModel_Definition_Model_Related_Abstract $related_model_definition
			 */
			$this->relations[$related_model_name] = new DataModel_Query_Relation_Inner(
				$related_model_definition,
				$related_model_definition->getMainModelRelationIDProperties()
			);

			return;
		}

		$m2n_related_data = null;

		//Try to find the appropriate MtoN relation
		foreach($this->main_data_model->getDataModelDefinition()->getProperties() as $property_definition) {

			if(!$property_definition->getIsDataModel()) {
				continue;
			}

			/**
			 * @var DataModel_Definition_Property_DataModel $property_definition
			 */
			$m2n_class_name = $property_definition->getDataModelClass();

			/**
			 * @var $m2n_instance DataModel_Related_MtoN
			 */
			$m2n_instance = Factory::getInstance($m2n_class_name);
			$m2n_definition = $m2n_instance->getDataModelDefinition();

			if( !($m2n_definition instanceof DataModel_Definition_Model_Related_MtoN) ) {
				continue;
			}

			//yes, it is THE MtoN relation
			if($m2n_class_name==$related_class_name) {
				$m2n_instance->setMRelatedModel($this->main_data_model);
				$n_model_instance = Factory::getInstance($m2n_instance->getNModelClassName());

				/**
				 *
				 * @var DataModel $n_model_instance
				 */
				$m2n_related_data = $m2n_instance->checkIsRelevantMtoNRelation($this->main_data_model, $n_model_instance, true);

				break;

			}


			/**
			 * @var DataModel $n_model_instance
			 */
			$n_model_instance = Factory::getInstance($related_class_name);

			$m2n_related_data = $m2n_instance->checkIsRelevantMtoNRelation($this->main_data_model, $n_model_instance, true);
			if($m2n_related_data) {
				break;
			}
		}

		if($m2n_related_data) {
			//... but we must drop old relation first
			unset($this->relations[$related_model_name]);
			foreach($m2n_related_data as $k=>$v) {
				$this->relations[$k]=$v;
			}

			return;
		}
		// ... and if yes, then setup relation by where from DataModel_Related_MtoN

		throw new DataModel_Query_Exception(
			'Unknown relation to class \''.$main_class_name.'\' <-> \''.$related_class_name.'\'',
			DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
		);


	}
}