<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
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
 *      - You can create your ID class or use one of those: DataModel_IDController_UniqueString, DataModel_IDController_Name, DataModel_IDController_AutoIncrement, DataModel_IDController_Passive
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
 *        #[DataModel_Definition(default_value: 'some default value')]
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
 *
 *
 *        Form field options (optional):
 *          #[DataModel_Definition(form_field_creator_method_name: 'someMethodName')]
 *                 Creator example:
 *                 public function myFieldCreator( DataModel_Definition_Property_Abstract $property_definition ) {
 *                     $form_field: $property_definition->getFormField();
 *                     $form_field->setLabel( 'Some special label' );
 *                     // ... do something with form field
 *                     return $form_field
 *                 }
 *
 *          #[DataModel_Definition(form_field_type: Form::TYPE_*)]
 *             - default: autodetect
 *          #[DataModel_Definition(form_field_is_required: bool)]
 *             - default: false
 *          #[DataModel_Definition(form_field_label: 'Field label:')]
 *          #[DataModel_Definition(form_field_options: ['option'=>'value','option'=>'value'])]
 *          #[DataModel_Definition(form_field_validation_regexp: '/some_regexp/')]
 *          #[DataModel_Definition(form_field_min_value: 1)]
 *          #[DataModel_Definition(form_field_max_value: 999)]
 *          #[DataModel_Definition(form_field_error_messages: ['error_code'=>'message','error_code'=>'message'])]
 *          #[DataModel_Definition(form_field_get_select_options_callback: callable)]
 *          #[DataModel_Definition(form_setter_name: 'setSomething')]
 *
 */


/**
 *
 */
abstract class DataModel extends BaseObject implements DataModel_Interface
{

	use DataModel_Trait;

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
