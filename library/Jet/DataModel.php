<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

	/**
	 * Available annotation:
	 *
	 * @JetDataModel:name = 'some_model_name'
	 *      - Internal model name. It is not name of database table! The name is used mainly in queries.
	 *
	 * @JetDataModel:database_table_name = 'some_table_name'
	 *
	 * @JetDataModel:id_controller_class_name = 'Id_Controller_Class_Name'
	 *      - You can create your ID class or use one of those: DataModel_IDController_UniqueString, DataModel_IDController_Name, DataModel_IDController_AutoIncrement, DataModel_IDController_Passive
	 *
	 * @JetDataModel:id_controller_options = ['option'=>'value', 'next_option'=>123]
	 *      - A practical example: @JetDataModel:id_controller_options = ['id_property_name'=>'some_id_property_name']
	 *
	 * @JetDataModel:parent_model_class_name = 'Parent_Class_Name'
	 *      - ONLY FOR RELATED MODELS!
	 *
	 * Relation on foreign model definition:
	 * @JetDataModel:relation = [ 'Some_Related_Class', [ 'property_name'=>'related_property_name', 'another_property_name' => 'another_related_property_name' ], DataModel_Query::JOIN_TYPE_*, ['some_required_related_model_name'] ]
	 *
	 *          Warning!
	 *          This kind of relation has no affect on saving or deleting object (like DataModel_Related_* models has).
	 *
	 * Composite keys definition:
	 * @JetDataModel:key = ['key_name', ['property_name', 'next_property_name'], DataModel::KEY_TYPE_*]
	 *
	 *
	 * Property definition:
	 *      /**
	 *       * Mandatory:
	 *       * @JetDataModel:type = DataModel::TYPE_*
	 *       * @JetDataModel:data_model_class = 'Some\Related_Model_Class_Name'
	 *       *      - specific for type DataModel::TYPE_DATA_MODEL*       *
	 *       * @JetDataModel:max_len = 255
	 *       *      - specific for type DataModel::TYPE_STRING
	 *       *
	 *       *
	 *       * Optional:
	 *       * @JetDataModel:database_column_name = 'some_column_name'
	 *       *      - property name is default database column name
	 *       * @JetDataModel:is_id = bool
	 *       * @JetDataModel:default_value = 'some default value'
	 *       * @JetDataModel:is_key = bool
	 *       *      - default: false (or default is true if is_id is true)
	 *       * @JetDataModel:key_type = DataModel::KEY_TYPE_*
	 *       *      - default: DataModel::KEY_TYPE_INDEX
	 *
	 *       * @JetDataModel:do_not_export = bool
	 *       *      - default: false
	 *       *      - Do not export property into the JSON result
	 *       * @JetDataModel:backend_options = ['BackendType'=>['option'=>'value','option'=>'value']]
	 *       *
	 *       *
	 *       *
	 *       * Form field options (optional):
	 *       *   @JetDataModel:form_field_creator_method_name = 'someMethodName'
	 *       *          Creator example:
	 *       *          public function myFieldCreator( DataModel_Definition_Property_Abstract $property_definition ) {
	 *       *              $form_field = $property_definition->getFormField();
	 *       *              $form_field->setLabel( 'Some special label' );
	 *       *              // ... do something with form field
	 *       *              return $form_field
	 *       *          }
	 *       *
	 *       *   @JetDataModel:form_field_type = Form::TYPE_*
	 *       *      - default: autodetect
	 *       *   @JetDataModel:form_field_is_required = bool
	 *       *      - default: false
	 *       *   @JetDataModel:form_field_label = 'Field label:'
	 *       *   @JetDataModel:form_field_options = ['option'=>'value','option'=>'value']
	 *       *   @JetDataModel:form_field_validation_regexp = '/some_regexp/'
	 *       *   @JetDataModel:form_field_min_value = 1
	 *       *   @JetDataModel:form_field_max_value = 999
	 *       *   @JetDataModel:form_field_error_messages = ['error_code'=>'message','error_code'=>'message']
	 *       *   @JetDataModel:form_field_get_select_options_callback = callable
	 *       *   @JetDataModel:form_setter_name = 'setSomething'
	 *       *
	 *       *
	 *       *
	 *       *
	 *       * @var string          //some PHP type ...
	 *       * /
	 *      protected $some_property = '';
	 *
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
	public function afterLoad()
	{

	}

	/**
	 *
	 */
	public function beforeSave()
	{

	}

	/**
	 *
	 */
	public function afterAdd()
	{

	}

	/**
	 *
	 */
	public function afterUpdate()
	{

	}

	/**
	 *
	 */
	public function afterDelete()
	{

	}

}
