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
	 * @var ?DataModel_Definition_Model
	 */
	protected ?DataModel_Definition_Model $data_model_definition = null;

	/**
	 *
	 * @var DataModel_Definition_Relation[]
	 */
	protected array $relations = [];


	/**
	 * @var ?DataModel_Query_Select
	 */
	protected ?DataModel_Query_Select $select = null;

	/**
	 * @var ?DataModel_Query_Where
	 */
	protected ?DataModel_Query_Where $where = null;

	/**
	 * @var ?DataModel_Query_Having
	 */
	protected ?DataModel_Query_Having $having = null;

	/**
	 *
	 * @var DataModel_Query_OrderBy|DataModel_Query_OrderBy_Item[]|null
	 */
	protected DataModel_Query_OrderBy|array|null $order_by = null;

	/**
	 *
	 * @var ?DataModel_Query_GroupBy
	 */
	protected ?DataModel_Query_GroupBy $group_by = null;

	/**
	 *
	 * @var int|null
	 */
	protected int|null $offset = null;

	/**
	 *
	 * @var int|null
	 */
	protected int|null $limit = null;

	/**
	 *
	 * @param DataModel_Definition_Model $main_data_model_definition
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( DataModel_Definition_Model $main_data_model_definition, array $where = [] ): DataModel_Query
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
	public function getDataModelDefinition(): DataModel_Definition_Model
	{
		return $this->data_model_definition;
	}

	/**
	 * @return ?DataModel_Query_Select
	 */
	public function getSelect(): ?DataModel_Query_Select
	{
		return $this->select;
	}

	/**
	 *
	 * @param array $items
	 *
	 * @return DataModel_Query|null
	 *
	 */
	public function setSelect( array $items ): DataModel_Query|null
	{
		$this->select = new DataModel_Query_Select( $this, $items );

		return $this;
	}

	/**
	 * @return DataModel_Query_Where|null
	 */
	public function getWhere(): DataModel_Query_Where|null
	{
		return $this->where;
	}

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public function setWhere( array $where ): DataModel_Query
	{
		$this->where = new DataModel_Query_Where( $this, $where );

		return $this;
	}

	/**
	 * @return DataModel_Query_Having|null
	 */
	public function getHaving(): DataModel_Query_Having|null
	{
		return $this->having;
	}

	/**
	 * @param array $having
	 *
	 * @return DataModel_Query
	 */
	public function setHaving( array $having ): DataModel_Query
	{
		$this->having = new DataModel_Query_Having( $this, $having );

		return $this;
	}

	/**
	 * @return DataModel_Query_GroupBy|null
	 */
	public function getGroupBy(): DataModel_Query_GroupBy|null
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
	public function setGroupBy( array|string $group_by ): DataModel_Query
	{
		$this->group_by = new DataModel_Query_GroupBy( $this, $group_by );

		return $this;
	}

	/**
	 * @return DataModel_Query_OrderBy|DataModel_Query_OrderBy_Item[]|null
	 */
	public function getOrderBy(): DataModel_Query_OrderBy|array|null
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
	public function setOrderBy( array|string $order_by ): DataModel_Query
	{
		$this->order_by = new DataModel_Query_OrderBy( $this, $order_by );

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getLimit(): int|null
	{
		return $this->limit;
	}

	/**
	 *
	 * @param int $limit
	 * @param int|null $offset (optional)
	 *
	 * @return DataModel_Query
	 */
	public function setLimit( int $limit, ?int $offset = null ): DataModel_Query
	{
		$this->limit = $limit;
		$this->offset = $offset === null ? null : $offset;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getOffset(): int|null
	{
		return $this->offset;
	}


	/**
	 * @return DataModel_Definition_Relation[]
	 */
	public function getRelations(): array
	{
		return $this->relations;
	}

	/**
	 * @param string $name
	 * @param DataModel_Definition_Relation $relation
	 */
	public function addRelation( string $name, DataModel_Definition_Relation $relation ): void
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
	public function setRelationJoinType( string $related_data_model_name, string $join_type ): DataModel_Query
	{
		$this->getRelation( $related_data_model_name )->setJoinType( $join_type );

		return $this;
	}

	/**
	 * @param string $related_data_model_name
	 *
	 * @return DataModel_Definition_Relation
	 * @throws DataModel_Query_Exception
	 *
	 */
	public function getRelation( string $related_data_model_name ): DataModel_Definition_Relation
	{
		if( !isset( $this->relations[$related_data_model_name] ) ) {
			throw new DataModel_Query_Exception(
				'Unknown relation \'' . $this->data_model_definition->getModelName() . '\' <-> \'' . $related_data_model_name . '\' Class: \'' . $this->data_model_definition->getClassName() . '\' ',
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
	 * @return DataModel_Definition_Property
	 * @throws DataModel_Query_Exception
	 *
	 */
	public function getPropertyAndSetRelation( string $property_name ): DataModel_Definition_Property
	{

		if( str_contains( $property_name, '.' ) ) {
			$property_name_parts = explode( '.', $property_name );

			if( count( $property_name_parts ) != 2 ) {
				throw new DataModel_Query_Exception(
					'Invalid property name: \'' . $property_name . '\'. Valid examples: property_name, related_data_model_name.property_name',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);

			}

			[
				$related_data_model_name,
				$property_name
			] = $property_name_parts;


			$relevant_relation = clone $this->data_model_definition->getRelation( $related_data_model_name );

			if( ($required_relations = $relevant_relation->getRequiredRelations()) ) {
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
				'Unknown property: \'' . $data_model_definition->getModelName() . '::' . $property_name . '\'',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		return $properties[$property_name];
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return DataModel_Backend::get( $this->data_model_definition )->createSelectQuery( $this );
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}
}