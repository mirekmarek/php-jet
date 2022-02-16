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
abstract class DataModel_Related_1to1 extends DataModel_Related
{

	/**
	 * @return string
	 */
	public static function dataModelDefinitionType(): string
	{
		return DataModel::MODEL_TYPE_RELATED_1TO1;
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
		 * @var DataModel_Definition_Model_Related_1to1 $definition
		 * @var DataModel $this
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