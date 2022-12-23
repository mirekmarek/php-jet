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
trait DataModel_Trait_Load
{

	/**
	 * @var ?DataModel_PropertyFilter
	 */
	protected ?DataModel_PropertyFilter $_load_filter = null;

	/**
	 * @return DataModel_PropertyFilter|null
	 */
	public function getLoadFilter(): DataModel_PropertyFilter|null
	{
		return $this->_load_filter;
	}

	/**
	 * @param ?DataModel_PropertyFilter $_load_filter
	 */
	protected function setLoadFilter( ?DataModel_PropertyFilter $_load_filter = null ) : void
	{
		$this->_load_filter = $_load_filter;
	}


	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( array $where = [] ): DataModel_Query
	{

		/**
		 * @var DataModel $this
		 */
		$query = new DataModel_Query( static::getDataModelDefinition() );
		$query->setWhere( $where );

		return $query;
	}


	/**
	 * @param array $this_data
	 * @param array $related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return static
	 */
	public static function initByData( array $this_data, array $related_data = [], DataModel_PropertyFilter $load_filter = null ): static
	{
		/**
		 * @var DataModel $_this
		 */
		$_this = new static();
		if( $load_filter ) {
			$_this->setLoadFilter( $load_filter );
		}

		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $this_definition
		 */
		$this_definition = static::getDataModelDefinition();


		foreach( $this_definition->getProperties() as $property_name => $property_definition ) {
			$property_definition->loadPropertyValue( $_this->{$property_name}, $this_data );
		}

		$_this->setIsSaved();

		$main_model_id = [];

		$this_is_related = false;

		if( $this_definition instanceof DataModel_Definition_Model_Main ) {
			foreach( $this_definition->getIdProperties() as $property_name => $property_definition ) {
				$main_model_id[$property_name] = $this_data[$property_name];
			}
		}

		if( $this_definition instanceof DataModel_Definition_Model_Related ) {
			$this_is_related = true;

			foreach( $this_definition->getMainModelRelationIdProperties() as $property_name => $property_definition ) {
				$related_to = $property_definition->getRelatedToPropertyName();

				$main_model_id[$related_to] = $this_data[$property_name];
			}
		}


		foreach( $this_definition->getProperties() as $property_name => $property_definition ) {

			if( !$property_definition instanceof DataModel_Definition_Property_DataModel ) {
				continue;
			}


			$class_name = $property_definition->getValueDataModelClass();

			$related_dm_definition = DataModel_Definition::get( $class_name );
			$related_model_name = $related_dm_definition->getModelName();

			if( !isset( $related_data[$related_model_name] ) ) {
				continue;
			}

			$this_related_data = [];

			foreach( $related_data[$related_model_name] as $r_i => $r_d ) {

				$is_related = true;
				foreach( $related_dm_definition->getMainModelRelationIdProperties() as $glue_property_definition ) {
					$this_property_name = $glue_property_definition->getName();
					$related_to = $glue_property_definition->getRelatedToPropertyName();

					if( $r_d[$this_property_name] != $main_model_id[$related_to] ) {
						$is_related = false;
						break;
					}
				}

				if( $is_related && $this_is_related ) {

					foreach( $related_dm_definition->getParentModelRelationIdProperties() as $glue_property_definition ) {
						$this_property_name = $glue_property_definition->getName();
						$related_to = $glue_property_definition->getRelatedToPropertyName();

						if( $r_d[$this_property_name] != $this_data[$related_to] ) {
							$is_related = false;
							break;
						}
					}

				}

				if( $is_related ) {
					$this_related_data[] = $r_d;
					unset( $related_data[$related_model_name][$r_i] );
				}
			}

			/**
			 * @var DataModel_Related $class_name
			 */

			$_this->{$property_name} = $class_name::initRelatedByData(
				$this_related_data,
				$related_data,
				$load_filter
			);

		}

		$_this->afterLoad();

		return $_this;
	}


	/**
	 * Loads DataModel.
	 *
	 * @param array|string|int|DataModel_IDController $id_or_where
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return static|null
	 */
	public static function load( array|string|int|DataModel_IDController $id_or_where,
	                             array|DataModel_PropertyFilter|null $load_filter = null ): static|null
	{
		/**
		 * @var DataModel_Definition_Model $this_definition
		 */
		$this_definition = static::getDataModelDefinition();

		if(
			$load_filter &&
			!($load_filter instanceof DataModel_PropertyFilter)
		) {
			$load_filter = new DataModel_PropertyFilter( $this_definition, $load_filter );
		}


		if( $id_or_where instanceof DataModel_IDController ) {
			$query = $id_or_where->getQuery();
		} else {
			$main_where = [];

			if( !is_array( $id_or_where ) ) {
				foreach( $this_definition->getIdProperties() as $id_property_name => $id_property_definition ) {
					$main_where[$id_property_name] = $id_or_where;
					break;
				}
			} else {
				$main_where = $id_or_where;
			}

			$query = static::createQuery( $main_where );

		}

		$query->setSelect( DataModel_PropertyFilter::getQuerySelect( $this_definition, $load_filter ) );

		/**
		 * @var DataModel_Backend $backend
		 */
		$backend = static::getBackendInstance();

		$this_data = $backend->fetchRow( $query );

		if( !$this_data ) {
			return null;
		}


		$main_model_id = [];

		if( $this_definition instanceof DataModel_Definition_Model_Main ) {
			foreach( $this_definition->getIdProperties() as $property_name => $property_definition ) {
				$main_model_id[$property_name] = $this_data[$property_name];
			}
		}

		if( $this_definition instanceof DataModel_Definition_Model_Related ) {
			foreach( $this_definition->getMainModelRelationIdProperties() as $property_name => $property_definition ) {
				$related_to = $property_definition->getRelatedToPropertyName();

				$main_model_id[$related_to] = $this_data[$property_name];
			}
		}


		$related_properties = $this_definition->getAllRelatedPropertyDefinitions();


		$related_data = [];
		foreach( $related_properties as $related_model_name => $related_property ) {

			if( $load_filter ) {
				if( !$load_filter->getModelAllowed( $related_model_name ) ) {
					continue;
				}
			}

			$class_name = $related_property->getValueDataModelClass();
			$related_dm_definition = DataModel_Definition::get( $class_name );

			$related_where = [];


			foreach( $related_dm_definition->getMainModelRelationIdProperties() as $main_related_property_definition ) {
				$property = $main_related_property_definition->getName();
				$related_to = $main_related_property_definition->getRelatedToPropertyName();

				if( $related_where ) {
					$related_where[] = 'AND';
				}
				$related_where[$property] = $main_model_id[$related_to];
			}
			
			/**
			 * @var DataModel_Related $class_name
			 */
			$_related_data = $class_name::fetchRelatedData( $related_where, $load_filter );

			if( !$_related_data ) {
				$_related_data = [];
			}

			$related_data[$related_model_name] = $_related_data;
		}


		return static::initByData(
			$this_data,
			$related_data
		);

	}

	/**
	 * @param array $where_per_model
	 * @param array|string|null $order_by
	 * @param callable|null $item_key_generator
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return static[]
	 */
	public static function fetch( array $where_per_model = [],
	                              array|string|null $order_by = null,
	                              ?callable $item_key_generator = null,
	                              array|DataModel_PropertyFilter|null $load_filter = null ): array
	{
		/**
		 * @var DataModel_Definition_Model $this_definition
		 */
		$this_definition = static::getDataModelDefinition();

		if(
			$load_filter &&
			!($load_filter instanceof DataModel_PropertyFilter)
		) {
			$load_filter = new DataModel_PropertyFilter( $this_definition, $load_filter );
		}


		$query = static::createQuery();

		$main_select = DataModel_PropertyFilter::getQuerySelect( $this_definition, $load_filter );
		$query->setSelect( $main_select );


		if( isset( $where_per_model[$this_definition->getModelName()] ) ) {
			$query->setWhere( $where_per_model[$this_definition->getModelName()] );
		}

		if( $order_by ) {
			$query->setOrderBy( $order_by );
		}


		/**
		 * @var DataModel_Backend $backend
		 */
		$backend = static::getBackendInstance();

		$this_data = $backend->fetchAll( $query );

		if( !$this_data ) {
			return [];
		}

		$main_model_ids = [];

		if( $this_definition instanceof DataModel_Definition_Model_Main ) {
			foreach( $this_data as $d ) {
				foreach( $this_definition->getIdProperties() as $property_name => $property_definition ) {
					if( !isset( $main_model_ids[$property_name] ) ) {
						$main_model_ids[$property_name] = [];
					}

					$main_model_ids[$property_name][] = $d[$property_name];
				}
			}
		}

		if( $this_definition instanceof DataModel_Definition_Model_Related ) {
			foreach( $this_data as $d ) {
				foreach( $this_definition->getMainModelRelationIdProperties() as $property_name => $property_definition ) {
					$related_to = $property_definition->getRelatedToPropertyName();

					if( !isset( $main_model_id[$related_to] ) ) {
						$main_model_id[$related_to] = [];
					}

					$main_model_id[$related_to][] = $d[$property_name];
				}
			}
		}

		$related_properties = $this_definition->getAllRelatedPropertyDefinitions();

		$related_data = [];
		foreach( $related_properties as $related_model_name => $related_property ) {

			if( $load_filter ) {
				if( !$load_filter->getModelAllowed( $related_model_name ) ) {
					continue;
				}
			}

			$class_name = $related_property->getValueDataModelClass();
			$related_dm_definition = DataModel_Definition::get( $class_name );

			$related_where = [];
			if( isset( $where_per_model[$related_dm_definition->getModelName()] ) ) {
				$related_where = $where_per_model[$related_dm_definition->getModelName()];
			}


			foreach( $related_dm_definition->getMainModelRelationIdProperties() as $main_related_property_definition ) {
				$property = $main_related_property_definition->getName();
				$related_to = $main_related_property_definition->getRelatedToPropertyName();

				if( $related_where ) {
					$related_where[] = 'AND';
				}
				$related_where[$property] = $main_model_ids[$related_to];
			}
			
			/**
			 * @var DataModel_Related $class_name
			 */
			$_related_data = $class_name::fetchRelatedData( $related_where, $load_filter );

			if( !$_related_data ) {
				$_related_data = [];
			}

			$related_data[$related_model_name] = $_related_data;
		}

		$items = [];
		foreach( $this_data as $m_i => $m_d ) {

			$_this = static::initByData( $m_d, $related_data, $load_filter );

			unset( $this_data[$m_i] );

			if( $item_key_generator ) {
				$key = $item_key_generator( $_this );

				$items[$key] = $_this;
			} else {
				$items[] = $_this;
			}

		}


		return $items;
	}
	
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @param string $fetch_method
	 * @return mixed
	 */
	protected static function dataFetch(
		string            $fetch_method,
		array             $select,
		array             $where = [],
		null|array|string $group_by = null,
		null|array        $having = null,
		null|string|array $order_by = null,
		null|int          $limit = null,
		null|int          $offset = null,
	): mixed
	{
		$query = static::createQuery( $where );
		
		$query->setSelect( $select );
		if( $group_by ) {
			$query->setGroupBy( $group_by );
		}
		
		if( $having ) {
			$query->setHaving( $having );
		}
		
		if( $order_by ) {
			$query->setOrderBy( $order_by );
		}
		
		if( $limit !== null ) {
			$query->setLimit( $limit, $offset );
		}
		
		/**
		 * @var DataModel_Backend $backend
		 */
		$backend = static::getBackendInstance();
		
		return $backend->{$fetch_method}( $query );
	}
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 *
	 * @return mixed
	 */
	public static function dataFetchAll( array $select,
	                                     array $where = [],
	                                     null|array|string $group_by = null,
	                                     null|array $having = null,
	                                     null|string|array $order_by = null,
	                                     null|int $limit=null,
	                                     null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchAll',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return mixed
	 */
	public static function dataFetchAssoc( array $select,
	                                       array $where = [],
	                                       null|array|string $group_by = null,
	                                       null|array $having = null,
	                                       null|string|array $order_by = null,
	                                       null|int $limit=null,
	                                       null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchAssoc',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return mixed
	 */
	public static function dataFetchCol( array $select,
	                                       array $where = [],
	                                       null|array|string $group_by = null,
	                                       null|array $having = null,
	                                       null|string|array $order_by = null,
	                                       null|int $limit=null,
	                                       null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchCol',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return mixed
	 */
	public static function dataFetchPairs( array $select,
	                                     array $where = [],
	                                     null|array|string $group_by = null,
	                                     null|array $having = null,
	                                     null|string|array $order_by = null,
	                                     null|int $limit=null,
	                                     null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchPairs',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return mixed
	 */
	public static function dataFetchRow( array $select,
	                                       array $where = [],
	                                       null|array|string $group_by = null,
	                                       null|array $having = null,
	                                       null|string|array $order_by = null,
	                                       null|int $limit=null,
	                                       null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchRow',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	
	/**
	 * @param array $select
	 * @param array $where
	 * @param array|string|null $group_by
	 * @param array|null $having
	 * @param string|array|null $order_by
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return mixed
	 */
	public static function dataFetchOne( array $select,
	                                     array $where = [],
	                                     null|array|string $group_by = null,
	                                     null|array $having = null,
	                                     null|string|array $order_by = null,
	                                     null|int $limit=null,
	                                     null|int $offset=null
	): mixed
	{
		return static::dataFetch(
			'fetchOne',
			$select,
			$where,
			$group_by,
			$having,
			$order_by,
			$limit,
			$offset
		);
	}
	/**
	 *
	 * @param array $where
	 * @param array $load_filter (optional)
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public static function fetchInstances( array $where = [], array $load_filter = [] ): DataModel_Fetch_Instances
	{

		$fetch = new DataModel_Fetch_Instances( static::createQuery( $where ) );
		if( $load_filter ) {
			$fetch->setLoadFilter( $load_filter );
		}

		return $fetch;
	}

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_IDs
	 */
	public static function fetchIDs( array $where = [] ): DataModel_Fetch_IDs
	{
		return new DataModel_Fetch_IDs( static::createQuery( $where ) );
	}

}