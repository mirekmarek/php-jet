<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 * Available attributes:
 *
 * #[DataModel_Definition(name: 'some_model_name')]
 *      - Internal model name. It is not name of database table! The name is used mainly in queries.
 *
 * #[DataModel_Definition(database_table_name: 'some_table_name')]
 *
 * #[DataModel_Definition(id_controller_class: Id_Controller_Class::class)]
 *      - You can create your ID class or use one of those: DataModel_IDController_UniqueString, DataModel_IDController_AutoIncrement, DataModel_IDController_Passive
 *
 * #[DataModel_Definition(id_controller_options: ['option'=>'value', 'next_option'=>123])]
 *      - A practical example: #[DataModel_Definition(id_controller_options: ['id_property_name'=>'some_id_property_name'])]
 *
 * #[DataModel_Definition(parent_model_class: Parent_Class::class)]
 *      - ONLY FOR RELATED MODELS!
 *
 * Relation on foreign model definition:
 * #[DataModel_Definition(relation: [
 *             'related_to_class_name'=> Some_Related_Class::class,
 *             'join_by_properties'=>[
 *                      'property_name'=>'related_property_name',
 *                      'another_property_name' => 'another_related_property_name'
 *              ],
 *              'join_type' => DataModel_Query::JOIN_TYPE_*,
 *              'required_relations' => ['some_required_related_model_name']
 *         ])]
 *
 *          Warning!
 *          This kind of relation has no effect on saving or deleting object (like DataModel_Related_* models has).
 *
 * Composite keys definition:
 * #[DataModel_Definition(key: ['key_name', ['property_name', 'next_property_name'], DataModel::KEY_TYPE_*])]
 *
 *
 * Property definition:
 *        Mandatory:
 *        #[DataModel_Definition(type: DataModel::TYPE_*)]
 *        #[DataModel_Definition(data_model_class: Related_Model_Class::class)]
 *             - specific for type DataModel::TYPE_DATA_MODEL*
 *        #[DataModel_Definition(max_len: 255)]
 *             - specific for type DataModel::TYPE_STRING
 *
 *
 *        Optional:
 *        #[DataModel_Definition(database_column_name: 'some_column_name')]
 *             - property name is default database column name
 *        #[DataModel_Definition(is_id: bool)]
 *        #[DataModel_Definition(is_key: bool)]
 *             - default: false (or default is true if is_id is true)
 *        #[DataModel_Definition(key_type: DataModel::KEY_TYPE_*)]
 *             - default: DataModel::KEY_TYPE_INDEX
 *
 *        #[DataModel_Definition(do_not_export: bool)]
 *             - default: false
 *             - Do not export property into the JSON result
 *        #[DataModel_Definition(backend_options: ['BackendType'=>['option'=>'value','option'=>'value']])]
 *
 */


/**
 *
 */
abstract class DataModel extends BaseObject implements BaseObject_Interface_Serializable_JSON, Form_Definition_Interface
{

	use DataModel_Trait_Definition;
	use DataModel_Trait_IDController;
	use DataModel_Trait_InternalState;
	use DataModel_Trait_MagicMethods;
	use DataModel_Trait_Backend;
	use DataModel_Trait_Load;
	use DataModel_Trait_Save;
	use DataModel_Trait_Delete;
	use DataModel_Trait_Exports;
	
	use Form_Definition_Trait;

	const MODEL_TYPE_MAIN = 'Main';
	const MODEL_TYPE_RELATED_1TON = 'Related_1toN';
	const MODEL_TYPE_RELATED_1TO1 = 'Related_1to1';


	const TYPE_ID = 'Id';
	const TYPE_ID_AUTOINCREMENT = 'IdAutoIncrement';
	const TYPE_STRING = 'String';
	const TYPE_BOOL = 'Bool';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_LOCALE = 'Locale';
	const TYPE_DATE = 'Date';
	const TYPE_DATE_TIME = 'DateTime';
	const TYPE_CUSTOM_DATA = 'CustomData';
	const TYPE_DATA_MODEL = 'DataModel';

	const KEY_TYPE_PRIMARY = 'PRIMARY';
	const KEY_TYPE_INDEX = 'INDEX';
	const KEY_TYPE_UNIQUE = 'UNIQUE';


	public function __construct()
	{
	}

	/**
	 *
	 */
	public function afterLoad(): void
	{

	}

	/**
	 *
	 */
	public function beforeSave(): void
	{

	}

	/**
	 *
	 */
	public function afterAdd(): void
	{

	}

	/**
	 *
	 */
	public function afterUpdate(): void
	{

	}

	/**
	 *
	 */
	public function afterDelete(): void
	{

	}

}
