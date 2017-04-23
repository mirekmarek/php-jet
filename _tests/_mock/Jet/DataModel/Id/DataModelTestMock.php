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
 *
 * @JetDataModel:name = 'data_model_test_mock'
 * @JetDataModel:database_table_name = 'data_model_test_mock'
 * @JetDataModel:id_class_name = 'Jet\DataModel_Id_UniqueString'
 */
class DataModel_Id_DataModelTestMock extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:is_id = true
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $id_property_1 = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:is_id = true
	 *
	 * @var Locale
	 */
	protected $id_property_2;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:is_id = true
	 *
	 * @var int
	 */
	protected $id_property_3 = 0;


	/**
	 */
	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {
	}
}