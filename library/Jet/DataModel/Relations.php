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
class DataModel_Relations extends BaseObject
{

	/**
	 * @var DataModel_Definition_Relation[][]
	 */
	protected static array $relations = [];

	/**
	 * @param string $data_model_class_name
	 * @param DataModel_Definition_Relation $relation
	 * @param bool $ignore_if_exists
	 */
	public static function add( string $data_model_class_name,
	                            DataModel_Definition_Relation $relation,
	                            bool $ignore_if_exists = false ): void
	{
		if( !isset( static::$relations[$data_model_class_name] ) ) {
			static::$relations[$data_model_class_name] = [];
		}

		$related_model_name = $relation->getRelatedDataModelName();


		if( isset( static::$relations[$data_model_class_name][$related_model_name] ) ) {
			if( $ignore_if_exists ) {
				return;
			}

			throw new DataModel_Exception(
				'Relation already exists! Class:' . $data_model_class_name . ', relation:' . $related_model_name,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		static::$relations[$data_model_class_name][$related_model_name] = $relation;
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Relation[]
	 */
	public static function get( string $data_model_class_name ): array
	{
		if( !array_key_exists( $data_model_class_name, static::$relations ) ) {
			static::$relations[$data_model_class_name] = [];

			DataModel_Definition::get( $data_model_class_name )->initRelations();
		}

		return static::$relations[$data_model_class_name];
	}

}