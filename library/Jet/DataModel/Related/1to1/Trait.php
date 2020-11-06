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

		return DataModel_Backend::get( $definition )->fetchRow( $query );
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
		return static::initByData( $this_data, $related_data, $load_filter );
	}


	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null      $property_filter
	 *
	 * @return Form_Field[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null )
	{

		$fields = [];

		$related_form = $this->getForm( '', $property_filter );

		foreach( $related_form->getFields() as $field ) {

			$field->setName( '/'.$parent_property_definition->getName().'/'.$field->getName() );

			$fields[] = $field;
		}


		return $fields;
	}


}
