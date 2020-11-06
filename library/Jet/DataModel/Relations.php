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
class DataModel_Relations extends BaseObject {

	/**
	 * @var DataModel_Definition_Relation[][]
	 */
	protected static $relations = [];

	/**
	 * @param string $data_model_class_name
	 * @param DataModel_Definition_Relation $relation
	 * @param bool $ignore_if_exists
	 */
	public static function add( $data_model_class_name, DataModel_Definition_Relation $relation, $ignore_if_exists=false )
	{
		if( !isset(static::$relations[$data_model_class_name]) ) {
			static::$relations[$data_model_class_name] = [];
		}

		$related_model_name = $relation->getRelatedDataModelName();


		if( isset(static::$relations[$data_model_class_name][$related_model_name]) ) {
			if($ignore_if_exists) {
				return;
			}

			throw new DataModel_Exception(
				'Relation already exists! Class:'.$data_model_class_name.', relation:'.$related_model_name,
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
	public static function get( $data_model_class_name )
	{
		if( !array_key_exists($data_model_class_name, static::$relations) ) {
			static::$relations[$data_model_class_name] = [];

			DataModel_Definition::get($data_model_class_name)->initRelations();
		}

		return static::$relations[$data_model_class_name];
	}
		
}