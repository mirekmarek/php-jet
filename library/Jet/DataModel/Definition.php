<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 */
class DataModel_Definition extends BaseObject implements BaseObject_Reflection_ParserInterface
{
	/**
	 *
	 * @var DataModel_Definition_Model[]
	 */
	protected static $__definitions = [];


	/**
	 * Returns model definition
	 *
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model
	 */
	public static function get( $class_name )
	{


		if( isset( self::$__definitions[$class_name] ) ) {
			return self::$__definitions[$class_name];
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

		$file_path = JET_DATAMODEL_DEFINITION_CACHE_PATH.str_replace( '\\', '__', $class_name.'.php' );

		if( JET_DATAMODEL_DEFINITION_CACHE_LOAD ) {

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
		self::$__definitions[(string)$class_name] = $class_name::_getDataModelDefinitionInstance( $class_name );

		if( JET_DATAMODEL_DEFINITION_CACHE_SAVE ) {
			IO_File::write(
				$file_path, '<?php return '.var_export( self::$__definitions[(string)$class_name], true ).';'
			);
		}

		return self::$__definitions[(string)$class_name];

	}

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 *
	 * @throws BaseObject_Reflection_Exception
	 */
	public static function parseClassDocComment( BaseObject_Reflection_ParserData $data )
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
					throw new BaseObject_Reflection_Exception(
						'Key definition parse error. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Example: @JetDataModel:key = [ \'some_key_name\', [ \'some_property_name_1\', \'some_property_name_2\', \'some_property_name_n\' ], DataModel::KEY_TYPE_INDEX ]',
						BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $value[2] ) ) {
					$value[2] = DataModel::KEY_TYPE_INDEX;
				}

				if( $value[2]!=DataModel::KEY_TYPE_INDEX&&$value[2]!=DataModel::KEY_TYPE_UNIQUE ) {
					throw new BaseObject_Reflection_Exception(
						'Unknown key type. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Use DataModel::KEY_TYPE_INDEX or DataModel::KEY_TYPE_UNIQUE',
						BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);
				}

				if( !isset( $data->result_data['data_model_keys_definition'] ) ) {
					$data->result_data['data_model_keys_definition'] = [];
				}

				if( isset( $data->result_data['data_model_keys_definition'][$value[0]] ) ) {
					throw new BaseObject_Reflection_Exception(
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
					throw new BaseObject_Reflection_Exception(
						'Relation definition parse error. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Example: @JetDataModel:relation = [ \'Some\RelatedClass\', [ \'property_name\'=>\'related_property_name\', \'another_property_name\' => \'another_related_property_name\' ], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]',
						BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $value[2] ) ) {
					$value[2] = DataModel_Query::JOIN_TYPE_LEFT_JOIN;
				}

				if( $value[2]!=DataModel_Query::JOIN_TYPE_LEFT_JOIN&&$value[2]!=DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ) {
					throw new BaseObject_Reflection_Exception(
						'Unknown relation type. Class: \''.$current_class_reflection->getName(
						).'\', definition: \''.$definition.'\', Use DataModel_Query::JOIN_TYPE_LEFT_JOIN or DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN',
						BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset( $data->result_data['data_model_outer_relations_definition'] ) ) {
					$data->result_data['data_model_outer_relations_definition'] = [];
				}

				if( isset( $data->result_data['data_model_outer_relations_definition'][$value[0]] ) ) {
					throw new BaseObject_Reflection_Exception(
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
					throw new BaseObject_Reflection_Exception(
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
				throw new BaseObject_Reflection_Exception(
					'Unknown definition! Class: \''.$current_class_reflection->getName(
					).'\', definition: \''.$definition.'\' ',
					BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( BaseObject_Reflection_ParserData $data )
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
