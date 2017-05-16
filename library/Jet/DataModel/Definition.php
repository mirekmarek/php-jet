<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Definition extends BaseObject implements Reflection_ParserInterface
{
	/**
	 * @var string
	 */
	protected static $cache_dir_path = JET_PATH_DATA.'datamodel_definitions/';

	/**
	 * @var bool
	 */
	protected static $cache_save_enabled;

	/**
	 * @var bool
	 */
	protected static $cache_load_enabled;

	/**
	 *
	 * @var DataModel_Definition_Model[]
	 */
	protected static $__definitions = [];

	/**
	 * @return string
	 */
	public static function getCacheDirPath()
	{
		return static::$cache_dir_path;
	}

	/**
	 * @param string $cache_dir_path
	 */
	public static function setCacheDirPath( $cache_dir_path )
	{
		static::$cache_dir_path = $cache_dir_path;
	}

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled()
	{
		if(static::$cache_save_enabled===null) {
			if(defined('JET_DATAMODEL_DEFINITION_CACHE_SAVE')) {
				static::$cache_save_enabled = JET_DATAMODEL_DEFINITION_CACHE_SAVE;
			} else {
				static::$cache_save_enabled = false;
			}
		}

		return static::$cache_save_enabled;
	}

	/**
	 * @param bool $cache_save_enabled
	 */
	public static function setCacheSaveEnabled( $cache_save_enabled )
	{
		static::$cache_save_enabled = $cache_save_enabled;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled()
	{
		if(static::$cache_load_enabled===null) {
			if(defined('JET_DATAMODEL_DEFINITION_CACHE_LOAD')) {
				static::$cache_load_enabled = JET_DATAMODEL_DEFINITION_CACHE_LOAD;
			} else {
				static::$cache_load_enabled = false;
			}
		}

		return static::$cache_load_enabled;
	}

	/**
	 * @param bool $cache_load_enabled
	 */
	public static function setCacheLoadEnabled( $cache_load_enabled )
	{
		static::$cache_load_enabled = $cache_load_enabled;
	}


	/**
	 * Returns model definition
	 *
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model
	 */
	public static function get( $class_name )
	{


		if( isset( static::$__definitions[$class_name] ) ) {
			return static::$__definitions[$class_name];
		}

		return static::_loadDefinition( $class_name );
	}

	/**
	 * @param string $class_name
	 *
	 * @return mixed
	 */
	protected static function _loadDefinition( $class_name )
	{

		$file_path = static::getCacheDirPath().str_replace( '\\', '__', $class_name.'.php' );

		if( static::getCacheLoadEnabled() ) {

			if( IO_File::exists( $file_path ) ) {
				/** @noinspection PhpIncludeInspection */
				$definition = require $file_path;

				static::$__definitions[$class_name] = $definition;

				return $definition;
			}
		}

		/**
		 * @var DataModel $class_name
		 */
		static::$__definitions[(string)$class_name] = $class_name::dataModelDefinitionFactory( $class_name );

		if( static::getCacheSaveEnabled() ) {
			IO_File::write(
				$file_path, '<?php return '.var_export( static::$__definitions[(string)$class_name], true ).';'
			);
		}

		return static::$__definitions[(string)$class_name];

	}

	/**
	 * @param Reflection_ParserData $data
	 *
	 * @throws Reflection_Exception
	 */
	public static function parseClassDocComment( Reflection_ParserData $data )
	{

		$current_class_reflection = $data->getCurrentHierarchyClassReflection();
		$definition = $data->getDefinition();


		switch( $data->getKey() ) {
			case 'key':
				$value = $data->getValueAsArray();

				if( !is_array( $value )||empty( $value[0] )||empty( $value[1] )||!is_array( $value[1] )||!is_string(
						$value[0]
					)
				) {
					throw new Reflection_Exception(
						'Key definition parse error. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Example: @JetDataModel:key = [ \'some_key_name\', [ \'some_property_name_1\', \'some_property_name_2\', \'some_property_name_n\' ], DataModel::KEY_TYPE_INDEX ]',
						Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $value[2] ) ) {
					$value[2] = DataModel::KEY_TYPE_INDEX;
				}

				if( $value[2]!=DataModel::KEY_TYPE_INDEX&&$value[2]!=DataModel::KEY_TYPE_UNIQUE ) {
					throw new Reflection_Exception(
						'Unknown key type. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Use DataModel::KEY_TYPE_INDEX or DataModel::KEY_TYPE_UNIQUE',
						Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);
				}

				if( !isset( $data->result_data['data_model_keys_definition'] ) ) {
					$data->result_data['data_model_keys_definition'] = [];
				}

				if( isset( $data->result_data['data_model_keys_definition'][$value[0]] ) ) {
					throw new Reflection_Exception(
						'Duplicate key! Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\''
					);

				}

				$data->result_data['data_model_keys_definition'][$value[0]] = [
					'name' => $value[0], 'type' => $value[1], 'property_names' => $value[2],
				];


				break;
			case 'relation':
				$value = $data->getValueAsArray();

				if( !is_array( $value )||empty( $value[0] )||empty( $value[1] )||!is_array( $value[1] )||!is_string(
						$value[0]
					)
				) {
					throw new Reflection_Exception(
						'Relation definition parse error. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Example: @JetDataModel:relation = [ \'Some\RelatedClass\', [ \'property_name\'=>\'related_property_name\', \'another_property_name\' => \'another_related_property_name\' ], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]',
						Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $value[2] ) ) {
					$value[2] = DataModel_Query::JOIN_TYPE_LEFT_JOIN;
				}

				if( $value[2]!=DataModel_Query::JOIN_TYPE_LEFT_JOIN&&$value[2]!=DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ) {
					throw new Reflection_Exception(
						'Unknown relation type. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Use DataModel_Query::JOIN_TYPE_LEFT_JOIN or DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN',
						Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $data->result_data['data_model_outer_relations_definition'] ) ) {
					$data->result_data['data_model_outer_relations_definition'] = [];
				}

				if( isset( $data->result_data['data_model_outer_relations_definition'][$value[0]] ) ) {
					throw new Reflection_Exception(
						'Duplicate relation! Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\''
					);

				}

				$data->result_data['data_model_outer_relations_definition'][$value[0]] = [
					'related_to_class_name' => $data->getRealClassName( $value[0] ), 'join_by_properties' => $value[1],
					'join_type'             => $value[2],
				];

				return;
				break;
			case 'name':
				if( !empty( $data->result_data['data_model_name'] ) ) {
					throw new Reflection_Exception(
						'@Jet_DataModel:model_name is defined by parent and can\'t be overloaded! '
					);

				}
				$data->result_data['data_model_name'] = $data->getValueAsString();
				break;
			case 'database_table_name':
				$data->result_data['database_table_name'] = $data->getValueAsString();
				break;
			case 'id_class_name':
				$data->result_data['data_model_id_class_name'] = $data->getValueAsClassName();
				break;
			case 'iterator_class_name':
				$data->result_data['iterator_class_name'] = $data->getValueAsClassName();
				break;
			case 'id_options':
				$data->result_data['id_options'] = $data->getValueAsArray();
				break;
			case 'parent_model_class_name':
				$data->result_data['data_model_parent_model_class_name'] = $data->getValueAsClassName();
				break;
			case 'M_model_class_name':
				$data->result_data['M_model_class_name'] = $data->getValueAsClassName();
				break;
			case 'N_model_class_name':
				$data->result_data['N_model_class_name'] = $data->getValueAsClassName();
				break;
			case 'default_order_by':
				$data->result_data['default_order_by'] = $data->getValueAsArray();
				break;
			default:
				throw new Reflection_Exception(
					'Unknown definition! Class: \''.$current_class_reflection->getName(
					).'\', definition: \''.$definition.'\' ',
					Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( Reflection_ParserData $data )
	{

		switch( $data->getKey() ) {
			case 'data_model_class':
				$data->setResultDataPropertyValue(
					'data_model_properties_definition', $data->getValueAsClassName()
				);
				break;
			case 'form_field_get_select_options_callback':
				$data->setResultDataPropertyValue(
					'data_model_properties_definition', $data->getValueAsCallback()
				);
				break;
			default:
				$data->setResultDataPropertyValue(
					'data_model_properties_definition', $data->getValue()
				);
				break;
		}
	}

}
