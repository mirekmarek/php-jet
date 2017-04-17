<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

/**
 * Class DataModel_Query_DataModel2TestMock
 *
 * @JetDataModel:name = 'data_model_2_test_mock'
 * @JetDataModel:database_table_name = 'data_model_2_test_mock'
 * @JetDataModel:ID_class_name = 'Jet\DataModel_ID_UniqueString'
 */
class DataModel_Query_DataModel2TestMock extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:do_not_export = true
	 * @JetDataModel:is_ID = false
	 * @JetDataModel:max_len = 123
	 * @JetDataModel:backend_options = [  'test' => ['option_1' => 'Option 1',  'option_2' => true,  'option_3' => 123,] ]
	 * @JetDataModel:form_field_label = 'Form field label'
	 * @JetDataModel:form_field_error_messages = array (  'error_1' => 'Error 1',  'error_2' => 'Error 2',  'error_3' => 'Error 3',)
	 * @JetDataModel:form_field_options = ['validation_regexp' => '/^([a-z0-9]{1,10})$/']
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_validation_regexp = '/^([a-z0-9]{1,10})$/'
	 *
	 * @var string
	 */
	protected $string_property = 'default value';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'DataModel_Query_DataModel2Related1TONTestMock'
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:do_not_export = true
	 *
	 * @var DataModel_Query_DataModel2Related1TONTestMock[]
	 */
	protected $data_model_property_1toN;


	public function _test_get_property_options( $property_name ) {
		$data = BaseObject_Reflection::get( get_called_class() , 'data_model_properties_definition', false);
		return $data[ $property_name ];
	}


	/**
	 */
	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {
	}

}