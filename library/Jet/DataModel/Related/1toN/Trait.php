<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
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
		$class_name = DataModel_Factory::getModelDefinitionClassNamePrefix().'Related_1toN';

		return new $class_name( $data_model_class_name );
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
		 * @var DataModel_Definition_Model_Related_1toN $definition
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
	 *
	 * @param array  $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function initRelatedByData( $this_data, array &$related_data, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Definition_Model_Related_1toN $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		$items = [];

		foreach( $this_data as $d ) {
			$items[] = static::initByData( $d, $related_data, $load_filter );
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
		$related_form = $this->getForm( '', $property_filter );

		return $related_form->getFields();
	}

}
