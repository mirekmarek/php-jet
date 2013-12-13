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

class DataModel_Definition_RelatedDataModelTestMock extends DataModel_Related_1toN {
	protected static $__data_model_parent_model_class_name = "Jet\\DataModel_Definition_DataModelTestMock";
	protected static $__data_model_model_name = "related_data_model_test_mock";

	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true,
		),
		"ID_property" => array(
			"type" => self::TYPE_STRING,
			"description" => "ID Description",
			"default_value" => "ID default value",
			"is_required" => false,
			"is_ID" => true,
			"max_len" => 50
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
		),
		"locale_property" => array(
			"type" => self::TYPE_LOCALE,
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"do_not_serialize" => true,
		),
		"int_property" => array(
			"type" => self::TYPE_INT,
			"description" => "Description",
			"default_value" => 2,
			"is_required" => true,
			"do_not_serialize" => true,
			"min_value" => 1,
			"max_value" => 4
		),
		"float_property" => array(
			"type" => self::TYPE_FLOAT,
			"description" => "Description",
			"default_value" => 2.0,
			"is_required" => true,
			"do_not_serialize" => true,
			"min_value" => 1.23,
			"max_value" => 4.56
		),
		"bool_property" => array(
			"type" => self::TYPE_BOOL,
			"description" => "Bool property:",
			"is_required" => false,
			"default_value" => true,
			"form_field_label" => "Bool property:"
		),
		"array_property" => array(
			"type" => self::TYPE_ARRAY,
			"item_type" => self::TYPE_STRING,
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"do_not_serialize" => false,
		),
		"date_time_property" => array(
			"type" => self::TYPE_DATE_TIME,
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"do_not_serialize" => true,

		),
		"date_property" => array(
			"type" => self::TYPE_DATE,
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"do_not_serialize" => true,

		),
		"data_model_property" => array(
			"type" => self::TYPE_DATA_MODEL,
			"data_model_class" => "Jet\\DataModel_Definition_DataModelTestMock",
			"description" => "Description",
			"default_value" => "default value",
			"is_required" => true,
			"do_not_serialize" => true,

		),

	);

	public function _test_get_property_options( $property_name ) {
		return static::$__data_model_properties_definition[$property_name];
	}


	/**
	 */
	public function __construct() {
	}

}