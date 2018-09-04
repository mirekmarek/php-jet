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
class DataModel_Query extends BaseObject
{
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
	const AVAILABLE_OPERATORS = [
		self::O_NOT_EQUAL,
		self::O_NOT_LIKE,
		self::O_GREATER_THAN_OR_EQUAL,
		self::O_LESS_THAN_OR_EQUAL,
		self::O_EQUAL,
		self::O_LIKE,
		self::O_GREATER_THAN,
		self::O_LESS_THAN,
	];

	/**
	 * @var DataModel_Definition_Model_Main
	 */
	protected $data_model_definition;

	/**
	 * The array key is related data model name
	 *
	 * @var DataModel_Definition_Relation[]
	 */
	protected $relations = [];


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
	 *
	 * @var DataModel_Query_OrderBy|DataModel_Query_OrderBy_Item[]
	 */
	protected $order_by;

	/**
	 *
	 * @var DataModel_Query_GroupBy
	 */
	protected $group_by;

	/**
	 *
	 * @var int
	 */
	protected $offset = null;

	/**
	 *
	 * @var int
	 */
	protected $limit = null;

	/**
	 *
	 * @param DataModel_Definition_Model $main_data_model_definition
	 * @param array                      $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( DataModel_Definition_Model $main_data_model_definition, array $where = [] )
	{

		$result = new static( $main_data_model_definition );
		if( $where ) {
			$result->setWhere( $where );
		}

		return $result;
	}



	/**
	 * @param DataModel_Definition_Model $main_data_model_definition
	 */
	public function __construct( DataModel_Definition_Model $main_data_model_definition )
	{
		$this->data_model_definition = $main_data_model_definition;

	}


	/**
	 * @return DataModel_Definition_Model
	 */
	public function getDataModelDefinition()
	{
		return $this->data_model_definition;
	}

	/**
	 * @return DataModel_Query_Select
	 */
	public function getSelect()
	{
		return $this->select;
	}

	/**
	 *
	 * @param array $items
	 *
	 * @return DataModel_Query
	 *
	 */
	public function setSelect( array $items )
	{
		$this->select = new DataModel_Query_Select( $this, $items );

		return $this;
	}

	/**
	 * @return DataModel_Query_Where
	 */
	public function getWhere()
	{
		return $this->where;
	}

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public function setWhere( array $where )
	{
		$this->where = new DataModel_Query_Where( $this, $where );

		return $this;
	}

	/**
	 * @return DataModel_Query_Having
	 */
	public function getHaving()
	{
		return $this->having;
	}

	/**
	 * @param array $having
	 *
	 * @return DataModel_Query
	 */
	public function setHaving( array $having )
	{
		$this->having = new DataModel_Query_Having( $this, $having );

		return $this;
	}

	/**
	 * @return DataModel_Query_GroupBy
	 */
	public function getGroupBy()
	{
		return $this->group_by;
	}

	/**
	 * Sets group by columns
	 *
	 * @param string[]|string $group_by
	 *
	 * @return DataModel_Query
	 *
	 */
	public function setGroupBy( $group_by )
	{
		$this->group_by = new DataModel_Query_GroupBy( $this, $group_by );

		return $this;
	}

	/**
	 * @return DataModel_Query_OrderBy|DataModel_Query_OrderBy_Item[]
	 */
	public function getOrderBy()
	{
		return $this->order_by;
	}

	/**
	 * Sets order by columns
	 *
	 * @param string[]|string $order_by
	 *
	 * @return DataModel_Query
	 *
	 */
	public function setOrderBy( $order_by )
	{
		$this->order_by = new DataModel_Query_OrderBy( $this, $order_by );

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * Sets limit (and offset)
	 *
	 * @param int $limit
	 * @param int $offset (optional)
	 *
	 * @return DataModel_Query
	 */
	public function setLimit( $limit, $offset = null )
	{
		$this->limit = (int)$limit;
		$this->offset = $offset===null ? null : (int)$offset;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getOffset()
	{
		return $this->offset;
	}


	/**
	 * @return DataModel_Definition_Relation[]
	 */
	public function getRelations()
	{
		return $this->relations;
	}

	/**
	 * @param string                        $name
	 * @param DataModel_Definition_Relation $relation
	 */
	public function addRelation( $name, DataModel_Definition_Relation $relation )
	{
		$this->relations[$name] = $relation;
	}

	/**
	 * Alias of query->getRelation('related_data_model_name')->setJoinType(join_type);
	 *
	 * @param string $related_data_model_name
	 * @param string $join_type
	 *
	 * @return DataModel_Query
	 */
	public function setRelationJoinType( $related_data_model_name, $join_type )
	{
		$this->getRelation( $related_data_model_name )->setJoinType( $join_type );

		return $this;
	}

	/**
	 * @param string $related_data_model_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Definition_Relation
	 */
	public function getRelation( $related_data_model_name )
	{
		if( !isset( $this->relations[$related_data_model_name] ) ) {
			throw new DataModel_Query_Exception(
				'Unknown relation \''.$this->data_model_definition->getModelName().'\' <-> \''.$related_data_model_name.'\' Class: \''.$this->data_model_definition->getClassName().'\' ',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $this->relations[$related_data_model_name];
	}


	/**
	 * Property_name examples:
	 *
	 * property_name
	 * related_model_name.property_name
	 *
	 *
	 * @param string $property_name
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getPropertyAndSetRelation( $property_name )
	{

		if(strpos($property_name, '.')!==false) {
			$property_name_parts = explode( '.', $property_name );

			if( count( $property_name_parts )!=2 ) {
				throw new DataModel_Query_Exception(
					'Invalid property name: \''.$property_name.'\'. Valid examples: property_name, related_data_model_name.property_name',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);

			}

			list($related_data_model_name, $property_name) = $property_name_parts;


			/**
			 * @var DataModel_Definition_Relation  $relevant_relation
			 */
			$relevant_relation = clone $this->data_model_definition->getRelation( $related_data_model_name );

			if( ( $required_relations = $relevant_relation->getRequiredRelations() ) ) {
				foreach( $required_relations as $required_relation ) {
					if( !isset( $this->relations[$required_relation] ) ) {
						$this->relations[$required_relation] = clone $this->data_model_definition->getRelation( $required_relation );
					}
				}
			}

			if( !isset( $this->relations[$related_data_model_name] ) ) {
				$this->relations[$related_data_model_name] = $relevant_relation;
			}

			$data_model_definition = $relevant_relation->getRelatedDataModelDefinition();

		} else {
			$data_model_definition = $this->data_model_definition;
		}



		$properties = $data_model_definition->getProperties();

		if( !isset( $properties[$property_name] ) ) {
			throw new DataModel_Query_Exception(
				'Unknown property: \''.$data_model_definition->getModelName().'::'.$property_name.'\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $properties[$property_name];
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return DataModel_Backend::get($this->data_model_definition)->createSelectQuery( $this );
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}
}