<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Related_1to1_Trait
 * @package Jet
 */
trait DataModel_Related_1to1_Trait
{
	use DataModel_Related_Trait;

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_1to1
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name )
	{
		return new DataModel_Definition_Model_Related_1to1( $data_model_class_name );
	}


	/**
	 * @param DataModel_Id                  $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array|mixed
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
	 * @param DataModel_Id                  $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel_Query
	 *
	 * @throws DataModel_Exception
	 */
	protected function getLoadRelatedDataQuery( DataModel_Id $main_id, DataModel_PropertyFilter $load_filter = null )
	{

		/**
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 * @var DataModel_Definition_Model_Related              $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );

		$select = DataModel_PropertyFilter::getQuerySelect( $data_model_definition, $load_filter );

		$query->setSelect( $select );
		$query->setWhere( [] );

		$where = $query->getWhere();

		foreach( $data_model_definition->getMainModelRelationIdProperties() as $property ) {
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

		return $query;
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
		 * @var DataModel_Definition_Model_Related_1to1 $definition
		 */
		$definition = static::getDataModelDefinition();

		$parent_id_values = [];
		if( $parent_id ) {

			foreach( $definition->getParentModelRelationIdProperties() as $property ) {

				/**
				 * @var DataModel_Definition_Property $property
				 */
				$parent_id_values[$property->getName()] = $parent_id[$property->getRelatedToPropertyName()];

			}
		}

		$model_name = $definition->getModelName();
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
				 * @var DataModel_Related_1to1 $loaded_instance
				 */
				$loaded_instance = new static();

				$loaded_instance->setLoadFilter( $load_filter );

				$loaded_instance->setState( $dat, $loaded_related_data );

				unset( $loaded_related_data[$model_name][$i] );

				return $loaded_instance;
			}
		}

		return null;
	}


	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter      $property_filter
	 *
	 * @return Form_Field[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null )
	{

		$fields = [];

		/**
		 * @var Form $related_form
		 */
		$related_form = $this->getForm( '', $property_filter );

		foreach( $related_form->getFields() as $field ) {

			$field->setName( '/'.$parent_property_definition->getName().'/'.$field->getName() );

			$fields[] = $field;
		}


		return $fields;
	}


}
