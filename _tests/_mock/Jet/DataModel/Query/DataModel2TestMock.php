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
 * Class DataModel_Query_DataModel2TestMock
 *
 * @JetDataModel:name = 'data_model_2_test_mock'
 * @JetDataModel:database_table_name = 'data_model_2_test_mock'
 */
class DataModel_Query_DataModel2TestMock extends DataModel {

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
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\\DataModel_Query_DataModel2Related1TONTestMock'
	 * @JetDataModel:description = 'Description'
	 * @JetDataModel:default_value = 'default value'
	 * @JetDataModel:is_required = true
	 * @JetDataModel:do_not_serialize = true
	 *
	 * @var DataModel_Query_DataModel2Related1TONTestMock[]
	 */
	protected $data_model_property_1toN;


	public function _test_get_property_options( $property_name ) {
		$data = $this->getDataModelPropertiesDefinitionData();
		return $data[ $property_name ];
	}


	/**
	 */
	public function __construct() {
	}

}