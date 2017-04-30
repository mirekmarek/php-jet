<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
 * @JetDataModel:id_class_name = 'Id_Class_Name'
 *      - You can create your ID class or use one of those: DataModel_Id_UniqueString, DataModel_Id_Name, DataModel_Id_AutoIncrement, DataModel_Id_Passive
 *
 * @JetDataModel:id_options = ['option'=>'value', 'next_option'=>123]
 *      - A practical example: @JetDataModel:id_options = ['id_property_name'=>'some_id_property_name']
 *
 * @JetDataModel:parent_model_class_name = 'Parent_Class_Name'
 *      - ONLY FOR RELATED MODELS!
 *
 * Overrides the default settings:
 *      @JetDataModel:forced_backend_type = 'SomeBackendType'
 *      @JetDataModel:forced_backend_config = ['option'=>'value','option'=>'value']
 *
 * Property definition:
 *      /**
 *       * @JetDataModel:database_column_name = 'some_column_name' (optional, property name is default database column name)
 *       * @JetDataModel:type = DataModel::TYPE_*
 *       * @JetDataModel:is_id = bool
 *       *      - optional
 *       * @JetDataModel:default_value = 'some default value'
 *       *      - optional
 *       * @JetDataModel:is_key = bool
 *       *      - optional, default: false (or default is true if is_id is true)
 *       * @JetDataModel:key_type = DataModel::KEY_TYPE_*
 *       *      - optional, default: DataModel::KEY_TYPE_INDEX
 *       * @JetDataModel:description = 'Some description ...'
 *       *      - optional
 *       * @JetDataModel:do_not_export = bool
 *       *      - Do not export property into the XML/JSON result
 *       *      - optional, default: false
 *       * @JetDataModel:backend_options = ['BackendType'=>['option'=>'value','option'=>'value']]
 *       *      - optional
 *       *
 *       * Specific (type DataModel::TYPE_DATA_MODEL):
 *       * @JetDataModel:data_model_class = 'Some\Related_Model_Class_Name'
 *       *
 *       * Specific (type DataModel::TYPE_STRING):
 *       * @JetDataModel:max_len = 255
 *       *
 *       *
 *       * Form field options:
 *       *   @JetDataModel:form_field_creator_method_name = 'someMethodName'
 *       *      - optional
 *       *          Creator example:
 *       *          public function myFieldCreator( DataModel_Definition_Property_Abstract $property_definition ) {
 *       *              $form_field = $property_definition->getFormField();
 *       *              $form_field->setLabel( 'Some special label' );
 *       *              // ... do something with form field
 *       *              return $form_field
 *       *          }
 *       *
 *       *   @JetDataModel:form_field_type = Form::TYPE_*
 *       *      - optional, default: autodetect
 *       *   @JetDataModel:form_field_is_required = bool
 *       *      - optional, default: false
 *       *   @JetDataModel:form_field_label = 'Field label:'
 *       *   @JetDataModel:form_field_options = ['option'=>'value','option'=>'value']
 *       *      - optional
 *       *   @JetDataModel:form_field_validation_regexp = '/some_regexp/'
 *       *      - optional
 *       *   @JetDataModel:form_field_min_value = 1
 *       *      - optional
 *       *   @JetDataModel:form_field_max_value = 999
 *       *      - optional
 *       *   @JetDataModel:form_field_error_messages = ['error_code'=>'message','error_code'=>'message']
 *       *   @JetDataModel:form_field_get_select_options_callback = callable
 *       *      - optional
 *       *   @JetDataModel:form_catch_value_method_name = 'someMethodName'
 *       *      - optional
 *       *
 *       *
 *       *
 *       *
 *       * @var string          //some PHP type ...
 *       * /
 *      protected $some_property;
 *
 *
 * Relation on foreign model definition:
 *      @JetDataModel:relation = [ 'Some\RelatedClass', [ 'property_name'=>'related_property_name', 'another_property_name' => 'another_related_property_name' ], DataModel_Query::JOIN_TYPE_* ]
 *
 *          Warning!
 *          This kind of relation has no affect on saving or deleting object (like DataModel_Related_* models has).
 *
 * Composite keys definition:
 *      @JetDataModel:key = ['key_name', ['property_name', 'next_property_name'], DataModel::KEY_TYPE_*]
 *
 */



/**
 * Class DataModel
 *
 */
abstract class DataModel extends BaseObject implements DataModel_Interface {

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
	const TYPE_ARRAY = 'Array';
	const TYPE_DATA_MODEL = 'DataModel';
	const TYPE_DYNAMIC_VALUE = 'DynamicValue';

	const KEY_TYPE_PRIMARY = 'PRIMARY';
	const KEY_TYPE_INDEX = 'INDEX';
	const KEY_TYPE_UNIQUE = 'UNIQUE';



    /**
     *
     */
    public function afterLoad() {

    }

	/**
	 *
	 */
    public function beforeSave() {

    }

    /**
     *
     */
    public function afterAdd() {

    }

    /**
     *
     */
    public function afterUpdate() {

    }

    /**
     *
     */
    public function afterDelete() {

    }

}
