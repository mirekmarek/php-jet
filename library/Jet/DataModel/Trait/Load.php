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
trait DataModel_Trait_Load
{

	/**
	 * @var DataModel_PropertyFilter
	 */
	protected $_load_filter;

	/**
	 * Loads DataModel.
	 *
	 * @param array|string|int               $id
	 * @param array|DataModel_PropertyFilter $load_filter
	 *
	 * @return DataModel
	 */
	public static function load( $id, $load_filter = null )
	{

		/**
		 * @var DataModel $_this
		 */
		$_this = new static();
		$_this->getIdObject()->init( $id );


		if( $load_filter ) {
			$_this->setLoadFilter( $load_filter );
		}

		$main_data = $_this->loadMainData();

		if( !$main_data ) {
			return null;
		}

		$_this->setState(
			$main_data,
			$_this->loadRelatedData()
		);

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $_this;
	}

	/**
	 * @return array|bool
	 */
	public function loadMainData()
	{
		/**
		 * @var DataModel         $this
		 * @var DataModel_Backend $backend
		 */

		$query = $this->getIdObject()->getQuery();

		$select = DataModel_PropertyFilter::getQuerySelect( static::getDataModelDefinition(), $this->getLoadFilter() );

		$query->setSelect( $select );
		$backend = static::getBackendInstance();

		return $backend->fetchRow( $query );

	}

	/**
	 * @return array|bool
	 */
	public function loadRelatedData()
	{
		/**
		 * @var DataModel                  $this
		 * @var DataModel_Definition_Model $definition
		 */

		$definition = static::getDataModelDefinition();
		$related_properties = $definition->getAllRelatedPropertyDefinitions();

		$related_data = [];
		foreach( $related_properties as $related_model_name => $related_property ) {
			/**
			 * @var DataModel_Definition_Property_DataModel $related_property
			 * @var DataModel_Related_Item_Interface        $class_name
			 */
			$class_name = $related_property->getValueDataModelClass();

			$_related_data = $class_name::loadRelatedData( $this->getIdObject(), $this->getLoadFilter() );

			if( !$_related_data ) {
				continue;
			}

			if( !isset( $related_data[$related_model_name] ) ) {
				$related_data[$related_model_name] = [];

				foreach( $_related_data as $rd ) {
					$related_data[$related_model_name][] = $rd;
				}
			}
		}

		return $related_data;
	}

	/**
	 * @return DataModel_PropertyFilter
	 */
	public function getLoadFilter()
	{
		return $this->_load_filter;
	}

	/**
	 * @param DataModel_PropertyFilter|array $_load_filter
	 */
	public function setLoadFilter( $_load_filter )
	{
		if( !$_load_filter ) {
			$this->_load_filter = null;

			return;
		}

		$definition = static::getDataModelDefinition();

		if( !( $_load_filter instanceof DataModel_PropertyFilter ) ) {
			$this->_load_filter = new DataModel_PropertyFilter( $definition, $_load_filter );
		} else {
			$this->_load_filter = $_load_filter;
		}
	}

	/**
	 * @param array $this_data
	 * @param array $related_data
	 */
	public function setState( array $this_data, $related_data = [] )
	{
		/**
		 * @var DataModel                  $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();


		foreach( $definition->getProperties() as $property_name => $property_definition ) {

			if( !( $this->{$property_name} instanceof DataModel_Related_Interface ) ) {
				$property_definition->loadPropertyValue( $this->{$property_name}, $this_data );
			}
		}
		$this->setIsSaved();

		$is_related = ( $this instanceof DataModel_Related_Interface );
		$parent_id = $is_related ? $this->getIdObject() : null;

		foreach( $definition->getProperties() as $property_name => $property_definition ) {

			if( ( $this->{$property_name} instanceof DataModel_Related_Interface ) ) {
				/**
				 * @var DataModel_Related_Item_Interface        $class_name
				 * @var DataModel_Definition_Property_DataModel $property_definition
				 */
				$class_name = $property_definition->getValueDataModelClass();

				$this->{$property_name} = $class_name::loadRelatedInstances(
					$related_data, $parent_id, $this->getLoadFilter()
				);
			}
		}

		$this->afterLoad();
	}
}