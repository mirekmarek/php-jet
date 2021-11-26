<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public static function dataModelDefinitionFactory( string $data_model_class_name ): DataModel_Definition_Model_Related_1to1
	{
		$class_name = Factory_DataModel::getModelDefinitionClassNamePrefix() . 'Related_1to1';

		return new $class_name( $data_model_class_name );
	}


	/**
	 *
	 * @param array $where
	 * @param ?DataModel_PropertyFilter $load_filter
	 *
	 * @return array
	 */
	public static function fetchRelatedData( array $where,
	                                         ?DataModel_PropertyFilter $load_filter = null ): array
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
	 * @param array $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return static
	 */
	public static function initRelatedByData( array $this_data,
	                                          array &$related_data,
	                                          DataModel_PropertyFilter $load_filter = null ): static
	{
		return static::initByData( $this_data, $related_data, $load_filter );
	}

}
