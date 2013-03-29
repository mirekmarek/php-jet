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

class DataModel_ID_DataModelTestMock extends DataModel {
	protected static $__data_model_model_name = "data_model_test_mock";


	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
		),
		"ID_property_1" => array(
			"type" => self::TYPE_STRING,
			"is_ID" => true,
			"max_len" => 50
		),
		"ID_property_2" => array(
			"type" => self::TYPE_LOCALE,
			"is_ID" => true
		),
		"ID_property_3" => array(
			"type" => self::TYPE_INT,
			"is_ID" => true
		)


	);


	/**
	 */
	public function __construct() {
	}

}