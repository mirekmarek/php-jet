<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;


/**
 * Class DataModel_Query_DataModelTestMock
 *
 * @JetDataModel:name = 'data_model_test_mock'
 * @JetDataModel:database_table_name = 'data_model_test_mock'
 */
class DataModel_Query_DataModelTestMock extends DataModel {

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:description = 'ID Description'
	 * @JetDataModel:default_value = 'ID default value'
	 * @JetDataModel:is_required = false
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $ID_property = 'ID default value';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:validation_regexp = '/^([a-z0-9]{1,10})$/'
	 * @JetDataModel:do_not_serialize = true
	 * @JetDataModel:is_ID = false
	 * @JetDataModel:max_len = 123
	 * @JetDataModel:backend_options = [  'test' => ['option_1' => 'Option 1',  'option_2' => true,  'option_3' => 123,] ]
	 * @JetDataModel:validation_method_name = 'string_validation_method_1'
	 * @JetDataModel:form_field_label = 'Form field label'
	 * @JetDataModel:form_field_error_messages = array (  'error_1' => 'Error 1',  'error_2' => 'Error 2',  'error_3' => 'Error 3',)
	 * @JetDataModel:form_field_options = array (  'option_1' => 'Option 1',  'option_2' => true,  'option_3' => 123,)
	 * @JetDataModel:list_of_valid_options = array (  0 => 'option1',  1 => 'option2',  2 => 'option3',  3 => '_#invalid',)
	 * @JetDataModel:error_messages = array (  'error_1' => 'Message 1',  'error_2' => 'Message 2',  'error_3' => 'Message 3',)
	 *
	 * @var string
	 */
	protected $string_property = 'default value';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 *
	 * @var Locale
	 */
	protected $locale_property;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 2
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 * @JetDataModel:min_value = 1
	 * @JetDataModel:max_value = 4
	 *
	 * @var int
	 */
	protected $int_property = 2;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_FLOAT
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 2.2
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 * @JetDataModel:min_value = 1.23
	 * @JetDataModel:max_value = 4.56
	 *
	 * @var float
	 */
	protected $float_property = 2.2;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:description = 'Bool property:'
	 * @JetDataModel:is_required = false
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_label = 'Bool property:'
	 *
	 * @var bool
	 */
	protected $bool_property = true;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = false
	 *
	 * @var array
	 */
	protected $array_property = 'default value';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATE_TIME
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 *
	 * @var DateTime
	 */
	protected $date_time_property;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATE
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 *
	 * @var DateTime
	 */
	protected $date_property;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\\DataModel_Query_DataModelRelated1TONTestMock'
	 *
	 * @var DataModel_Query_DataModelRelated1TONTestMock[]
	 */
	protected $data_model_property_1toN;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\\DataModel_Query_DataModelRelated1TO1TestMock'
	 *
	 * @var DataModel_Query_DataModelRelated1TO1TestMock[]
	 */
	protected $data_model_property_1to1;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\\DataModel_Query_DataModelRelatedMTONTestMock'
	 *
	 * @var DataModel_Query_DataModelRelatedMTONTestMock[]
	 */
	protected $data_model_property_MtoN;


	protected static $__test_data_model_forced_backend_type = null;
	protected static $__test_data_model_forced_backend_config = null;

	public function _test_get_property_options( $property_name ) {
		$data = Object_Reflection::get( get_called_class() , 'data_model_properties_definition', false);
		return $data[ $property_name ];
	}


	/**
	 */
	public function __construct() {
	}


	public static function setBackendType( $backend_type ) {
		static::getDataModelDefinition()->__test_set('forced_backend_type', $backend_type);
		DataModel_Definition_Model_Abstract::__test_set_static('__backend_instances', array());
	}

	public static function setBackendOptions( $backend_config ) {
		static::getDataModelDefinition()->__test_set('forced_backend_config', $backend_config);
		DataModel_Definition_Model_Abstract::__test_set_static('__backend_instances', array());
	}

}