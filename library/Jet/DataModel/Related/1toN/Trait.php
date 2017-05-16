<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Related_1toN_Trait
 * @package Jet
 */
trait DataModel_Related_1toN_Trait
{
	use DataModel_Related_Trait;

	/**
	 * @var array
	 */
	protected static $load_related_data_order_by = [];

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name )
	{
		return new DataModel_Definition_Model_Related_1toN( $data_model_class_name );
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
		 * @var DataModel_Definition_Model_Related_1toN $definition
		 */
		$definition = static::getDataModelDefinition();

		if( $load_filter ) {
			if( !$load_filter->getModelAllowed( $definition->getModelName() ) ) {
				return [];
			}
		}

		$query = static::getLoadRelatedDataQuery( $main_id, $load_filter );

		return DataModel_Backend::get( $definition )->fetchAll( $query );
	}

	/**
	 * @param DataModel_Id             $main_id
	 * @param DataModel_PropertyFilter $load_filter
	 *
	 * @return DataModel_Query
	 */
	protected static function getLoadRelatedDataQuery( DataModel_Id $main_id, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Definition_Model_Related_1toN $definition
		 */
		$definition = static::getDataModelDefinition();

		/**
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 */

		$query = new DataModel_Query( $definition );

		$select = DataModel_PropertyFilter::getQuerySelect( $definition, $load_filter );

		$query->setSelect( $select );
		$query->setWhere( [] );

		$where = $query->getWhere();

		foreach( $definition->getMainModelRelationIdProperties() as $property ) {
			/**
			 * @var DataModel_Definition_Property $property
			 */
			$property_name = $property->getRelatedToPropertyName();
			$value = $main_id[$property_name];

			$where->addAND();
			$where->addExpression(
				$property, DataModel_Query::O_EQUAL, $value
			);
		}

		$order_by = static::getLoadRelatedDataOrderBy();
		if( $order_by ) {
			$query->setOrderBy( $order_by );
		}


		return $query;
	}

	/**
	 * @return array
	 */
	public static function getLoadRelatedDataOrderBy()
	{
		/**
		 * @var DataModel_Definition_Model_Related_1toN $definition
		 */
		$definition = static::getDataModelDefinition();

		return static::$load_related_data_order_by ? static::$load_related_data_order_by :
			$definition->getDefaultOrderBy();
	}

	/**
	 * @param array $order_by
	 */
	public static function setLoadRelatedDataOrderBy( array $order_by )
	{
		static::$load_related_data_order_by = $order_by;
	}

	/**
	 * @param array                         &$loaded_related_data
	 * @param DataModel_Id|null             $parent_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function loadRelatedInstances( array &$loaded_related_data, DataModel_Id $parent_id = null, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Definition_Model_Related_1toN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$parent_id_values = [];
		if( $parent_id ) {

			foreach( $data_model_definition->getParentModelRelationIdProperties() as $property ) {

				/**
				 * @var DataModel_Definition_Property $property
				 */
				$parent_id_values[$property->getName()] = $parent_id[$property->getRelatedToPropertyName()];

			}
		}


		$model_name = $data_model_definition->getModelName();
		$items = [];

		if( !empty( $loaded_related_data[$model_name] ) ) {
			foreach( $loaded_related_data[$model_name] as $i => $dat ) {
				if( $parent_id_values ) {
					foreach( $parent_id_values as $k => $v ) {
						if( $dat[$k]!=$v ) {
							continue 2;
						}
					}
				}

				/**
				 * @var DataModel_Related_1toN $loaded_instance
				 */
				$loaded_instance = new static();
				$loaded_instance->setLoadFilter( $load_filter );

				$loaded_instance->setState( $dat, $loaded_related_data );

				unset( $loaded_related_data[$model_name][$i] );

				/**
				 * @var DataModel_Related_1toN $loaded_instance
				 */
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
		}

		/**
		 * @var DataModel_Related_1toN_Iterator $iterator
		 */

		$iterator_class_name = $data_model_definition->getIteratorClassName();

		$iterator = new $iterator_class_name( $data_model_definition, $items );

		return $iterator;
	}

	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue()
	{
		return null;
	}

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance()
	{
		/**
		 * @var DataModel_Definition_Model_Related_1toN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$iterator_class_name = $data_model_definition->getIteratorClassName();

		$i = new $iterator_class_name( $data_model_definition );

		return $i;
	}

	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field[]
	 *
	 */
	public function getRelatedFormFields( /** @noinspection PhpUnusedParameterInspection */
		DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null )
	{
		/**
		 * @var Form $related_form
		 */
		$related_form = $this->getForm( '', $property_filter );

		return $related_form->getFields();
	}

}
