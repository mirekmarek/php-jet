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
			"is_ID" => true,
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

	/**
	 * @param DataModel_ID_Abstract|string $ID
	 * @return bool
	 */
	public function getIDExists( $ID ) {
		if( !($ID instanceof DataModel_ID_Abstract) ) {
			$ID = $this->getEmptyIDInstance()->unserialize($ID);
		}

		$input = $ID["ID"];

		if($input=="site_1" || $input=="site_11") {
			return true;
		}

		if(
				$input=="long_long_long_long_long_long_long_long_long_long_" ||
				$input=="long_long_long_long_long_long_long_long_long_l1" ||
				$input=="long_long_long_long_long_long_long_long_long_l2" ||
				$input=="long_long_long_long_long_long_long_long_long_l3" ||
				$input=="long_long_long_long_long_long_long_long_long_l4"
		) {
			return true;
		}


		return false;
	}
}