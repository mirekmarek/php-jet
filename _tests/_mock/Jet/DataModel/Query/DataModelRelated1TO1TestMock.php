<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

class DataModel_Query_DataModelRelated1TO1TestMock extends DataModel_Related_1to1 {
	protected static $__data_model_parent_model_class_name = "Jet\\DataModel_Query_DataModelTestMock";

	protected static $__data_model_model_name = "data_model_test_mock_related_1to1";


	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
		),
		"string_property" => array(
			"type" => self::TYPE_STRING,
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"validation_regexp" => "/^([a-z0-9]{1,10})$/",
			"do_not_serialize" => true,
			"is_ID" => false,
			"max_len" => 123,
			"backend_options" => array(
				"option_1" => "Option 1",
				"option_2" => true,
				"option_3" => 123
			),
			"validation_method_name" => "string_validation_method_1",
			"form_field_label" => "Form field label",
			"form_field_error_messages" => array(
				"error_1" => "Error 1",
				"error_2" => "Error 2",
				"error_3" => "Error 3",
			),
			"form_field_options" => array(
				"option_1" => "Option 1",
				"option_2" => true,
				"option_3" => 123
			),
			"list_of_valid_options" => array(
				"option1",
				"option2",
				"option3",
				"_#invalid"
			),
			"error_messages" => array(
				"error_1" => "Message 1",
				"error_2" => "Message 2",
				"error_3" => "Message 3",
			),
		)
	);

	public function _test_get_property_options( $property_name ) {
		return static::$__data_model_properties_definition[$property_name];
	}


	/**
	 */
	public function __construct() {
	}

}