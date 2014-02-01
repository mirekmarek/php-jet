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
	 * @var DataModel_Definition_Model_Main
	 */
	protected $main_data_model_definition;

	/**
	 * The array key is related data model name
	 *
	 * @var DataModel_Definition_Relation_Abstract[]
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
	 * @param DataModel $main_data_model
	 */
	public function __construct( DataModel $main_data_model ) {
		$this->setMainDataModel($main_data_model);

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
	 * @param DataModel $main_data_model
	 */
	public function setMainDataModel( DataModel $main_data_model) {
		$this->main_data_model = $main_data_model;
		$this->main_data_model_definition = $main_data_model->getDataModelDefinition();
		$this->main_data_model_class_name = $this->main_data_model_definition->getClassName();
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
	 * @return DataModel_Definition_Relation_Abstract[]
	 */
	public function getRelations() {
		return $this->relations;
	}

	/**
	 * @param string $related_data_model_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Definition_Relation_Abstract
	 */
	public function getRelation( $related_data_model_name ) {
		if(!isset($this->relations[$related_data_model_name])) {
			throw new DataModel_Query_Exception(
				'Unknown relation \''.$this->main_data_model_definition->getModelName().'\' <-> \''.$related_data_model_name.'\' Class: \''.$this->main_data_model_class_name.'\' ',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $this->relations[$related_data_model_name];
	}

	/**
	 * Alias of query->getRelation('related_data_model_name')->setJoinType(join_type);
	 *
	 * @param $related_data_model_name
	 * @param $join_type
	 *
	 * @return DataModel_Query
	 */
	public function setRelationJoinType( $related_data_model_name, $join_type ) {
		$this->getRelation($related_data_model_name)->setJoinType($join_type);

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
	 * @param string $str_property_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function _getPropertyAndSetRelation( $str_property_name ) {
		$property_name_parts = explode('.', $str_property_name);

		if(count($property_name_parts)<2) {
			throw new DataModel_Query_Exception(
				'Invalid property name: \''.$str_property_name.'\'. Valid examples: this.property_name, related_data_model_name.property_name, related_data_model.next_related_data_model.property_name, ...',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}


		$data_model_definition = $this->main_data_model_definition;

		if($property_name_parts[0]=='this') {
			array_shift($property_name_parts);

			$property_name = array_shift( $property_name_parts );

			if( $property_name_parts ) {
				throw new DataModel_Query_Exception(
					'Invalid property name: \''.$property_name.'\'. Valid examples: this.property_name, related_data_model_name.property_name, related_data_model.next_related_data_model.property_name, ...',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}

		} else {

			$property_name = array_pop( $property_name_parts );

			var_dump($str_property_name, $property_name, $property_name_parts);

			do {
				$related_data_model_name = array_shift( $property_name_parts );

				/**
				 * @var DataModel_Definition_Relation_Abstract[] $_relations
				 */
				$_relations = $data_model_definition->getRelations();

				if(!isset($_relations[$related_data_model_name])) {

					throw new DataModel_Query_Exception(
						'Unknown relation to class \''.$data_model_definition->getModelName().'\' <-> \''.$part.'\' (Class: \''.$this->main_data_model_class_name.'\') ',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
				}

				$relevant_relation = clone $_relations[$related_data_model_name];

				if( ($required_relations=$relevant_relation->getRequiredRelations()) ) {
					foreach($required_relations as $required_relation) {
						$this->relations[$required_relation] = clone $_relations[$required_relation];
					}
				}

				$this->relations[$related_data_model_name]=$relevant_relation;

				$data_model_definition = $relevant_relation->getRelatedDataModelDefinition();

			} while( $property_name_parts );

		}


		$properties = $data_model_definition->getProperties();

		if( !isset($properties[$property_name]) ) {
			throw new DataModel_Query_Exception(
				'Unknown property: \''.$property_name.'\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $properties[$property_name];


		//TODO: toto je uplne blbe ..
		/*
		$property = null;
		while($property_name_parts) {
			$part = array_shift($property_name_parts);
			$properties = $data_model_definition->getProperties();

			if( !isset($properties[$part]) ) {
				throw new DataModel_Query_Exception(
					'Unknown property: \''.$property_name.'\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}

			$property = $properties[$part];

			if($property->getIsDataModel()) {
				 * @var DataModel_Definition_Property_DataModel $property
				$data_model_definition = DataModel::getDataModelDefinition($property->getDataModelClass());
				$is_main_do = false;
				unset($property);
				$property = null;
			}
		}
		*/

		if(!$property) {
			throw new DataModel_Query_Exception(
				'Unknown property: \''.$property_name.'\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}


		return $property;
	}
}