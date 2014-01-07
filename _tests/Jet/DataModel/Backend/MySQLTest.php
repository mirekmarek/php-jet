<?php
namespace Jet;

require_once "_mock/Jet/DataModel/Query/DataModelTestMock.php";
require_once "_mock/Jet/DataModel/Query/DataModelRelated1TONTestMock.php";
require_once "_mock/Jet/DataModel/Query/DataModelRelated1TO1TestMock.php";
require_once "_mock/Jet/DataModel/Query/DataModelRelatedMTONTestMock.php";
require_once "_mock/Jet/DataModel/Query/DataModel2TestMock.php";
require_once "_mock/Jet/DataModel/Query/DataModel2Related1TONTestMock.php";

class DataModel_Backend_MySQLTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Backend_MySQL
	 */
	protected $object;

	/**
	 * @var DataModel_Backend_MySQL_Config
	 */
	protected $config;

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;
	protected $properties;

	protected $select_data;
	protected $where_data;
	protected $having_data;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$this->config = new DataModel_Backend_MySQL_Config( true );

		$this->object = new DataModel_Backend_MySQL( $this->config );

		$this->data_model = new DataModel_Query_DataModelTestMock();
		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->select_data = array(
			$this->properties["ID_property"],
			"my_string_property" => $this->properties["string_property"],
			"my_sum" => [
				[
					$this->properties["int_property"],
					$this->properties["float_property"]
				],
				"SUM(%int_property%)+%float_property%"
			],
			"my_count" => [
				"this.int_property",
				"COUNT(%int_property%)"
			],
			"this.data_model_property_1toN.string_property"
		);

		$this->where_data = array(
			"data_model_property_MtoN.string_property" => "test",
			"AND",
			"this.int_property =" => 1234,
			"AND",
			"this.int_property >" => 2,
			"AND",
			"this.int_property <" => 9999,
			"AND",
			"this.int_property >=" => 3,
			"AND",
			"this.int_property <=" => 9998,
			"AND",
			"this.string_property !=" => "test",
			"OR",
			[
				"this.ID_property *" => "test%",
				"AND",
				"this.int_property" => 54321
			]
		);

		$this->having_data = array(
			"my_count =" => 1234,
			"AND",
			"my_string_property !=" => "test",
			"OR",
			[
				"my_string_property *" => "test%",
				"AND",
				"my_count" => 54321
			]
		);


	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\DataModel_Backend_MySQL::helper_getCreateCommand
	 */
	public function testHelper_getCreateCommand() {
		$data_model = new DataModel_Query_DataModelTestMock();

		$q = $this->object->helper_getCreateCommand($data_model);

		$valid_q = "CREATE TABLE IF NOT EXISTS `data_model_test_mock` (\n"
				."\t`ID` varchar(50) COLLATE utf8_bin NOT NULL,\n"
				."\t`ID_property` varchar(50) COLLATE utf8_bin NOT NULL,\n"
				."\t`string_property` varchar(123),\n"
				."\t`locale_property` varchar(20) COLLATE utf8_bin NOT NULL,\n"
				."\t`int_property` int,\n"
				."\t`float_property` float,\n"
				."\t`bool_property` tinyint(1),\n"
				."\t`array_property` longtext,\n"
				."\t`date_time_property` datetime,\n"
				."\t`date_property` date\n"
				."\t,PRIMARY KEY (`ID`, `ID_property`)\n"
				.") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;\n\n";

		$this->assertEquals($valid_q, $q);
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::helper_getDropCommand
	 */
	public function testHelper_getDropCommand() {
		$data_model = new DataModel_Query_DataModelTestMock();

		$valid_q = "RENAME TABLE `data_model_test_mock` TO `_d".date("YmdHis")."data_model_test_mock`";

		$q = $this->object->helper_getDropCommand( $data_model );

		$this->assertEquals($valid_q, $q);
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::helper_getUpdateCommand
	 * @todo   Implement testHelper_getUpdateCommand().
	 */
	public function testHelper_getUpdateCommand() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendSelectQuery
	 */
	public function testGetBackendSelectQuery() {

		$query = DataModel_Query::createQuery($this->data_model, $this->where_data);
		$query->setSelect($this->select_data)
			->setRelationJoinType("data_model_test_mock_related_MtoN", DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setRelationJoinType("data_model_test_mock_related_1toN", DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setHaving($this->having_data)
			->setOrderBy( array("+my_string_property", "-my_count", "this.int_property") )
			->setGroupBy( array("ID_property", "my_string_property", "this.int_property") )
			->setLimit(100, 10);

		$valid_query =
				"SELECT\n"
					."\t`data_model_test_mock`.`ID_property` AS `ID_property`,\n"
					."\t`data_model_test_mock`.`string_property` AS `my_string_property`,\n"
					."\tSUM(`data_model_test_mock`.`int_property`)+`data_model_test_mock`.`float_property` AS `my_sum`,\n"
					."\tCOUNT(`data_model_test_mock`.`int_property`) AS `my_count`,\n"
					."\t`data_model_test_mock_related_1ton`.`string_property` AS `string_property`\n"
				."FROM\n"
					."\t`data_model_test_mock`\n"
						."\t\tLEFT OUTER JOIN `data_model_test_mock_related_mton` ON\n"
							."\t\t\t`data_model_test_mock_related_mton`.`data_model_test_mock_ID` = `data_model_test_mock`.`ID` AND \n"
							."\t\t\t`data_model_test_mock_related_mton`.`data_model_test_mock_ID_property` = `data_model_test_mock`.`ID_property`\n"
						."\t\tJOIN `data_model_2_test_mock` ON\n"
							."\t\t\t`data_model_2_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_2_test_mock_ID`\n"
						."\t\tLEFT OUTER JOIN `data_model_test_mock_related_1ton` ON\n"
							."\t\t\t`data_model_test_mock_related_1ton`.`data_model_test_mock_ID` = `data_model_test_mock`.`ID` AND \n"
							."\t\t\t`data_model_test_mock_related_1ton`.`data_model_test_mock_ID_property` = `data_model_test_mock`.`ID_property`\n"
				."WHERE\n"
					."\t`data_model_2_test_mock`.`string_property`='test' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`='1234' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`>'2' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`<'9999' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`>='3' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`<='9998' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`string_property`<>'test' \n"
					."\tOR\n "
					."\t(\n"
						."\t\t`data_model_test_mock`.`ID_property` LIKE 'test%'\n"
						."\t\tAND\n"
						." \t\t`data_model_test_mock`.`int_property`='54321'  \n"
						."\t)\n\n"
				."GROUP BY\n"
					."\tID_property,\n"
					."\tmy_string_property,\n"
					."\t`data_model_test_mock`.`int_property`\n\n"
				."HAVING\n"
					."\tmy_count='1234' \n"
					."\tAND\n"
					." \tmy_string_property<>'test' \n"
					."\tOR\n "
					."\t(\n"
						."\t\tmy_string_property LIKE 'test%'\n"
						."\t\tAND\n "
						."\t\tmy_count='54321'  \n"
					."\t)\n\n"
				."ORDER BY\n"
					."\tmy_string_property ASC,\n"
					."\tmy_count DESC,\n"
					."\t`data_model_test_mock`.`int_property` ASC\n\n"
				."LIMIT 10,100\n";


		$this->assertEquals($valid_query, $this->object->getBackendSelectQuery( $query));
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendCountQuery
	 */
	public function testGetBackendCountQuery() {
		$query = DataModel_Query::createQuery($this->data_model, $this->where_data);
		$query->setSelect($this->select_data)
			->setRelationJoinType("data_model_test_mock_related_MtoN", DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setRelationJoinType("data_model_test_mock_related_1toN", DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setHaving($this->having_data)
			->setOrderBy( array("+my_string_property", "-my_count") )
			->setGroupBy( array("ID_property", "my_string_property") )
			->setLimit(100, 10);


		$valid_query = "SELECT count(*) FROM\n"
					."\t`data_model_test_mock`\n"
					."\t\tLEFT OUTER JOIN `data_model_test_mock_related_mton` ON\n"
						."\t\t\t`data_model_test_mock_related_mton`.`data_model_test_mock_ID` = `data_model_test_mock`.`ID` AND \n"
						."\t\t\t`data_model_test_mock_related_mton`.`data_model_test_mock_ID_property` = `data_model_test_mock`.`ID_property`\n"
					."\t\tJOIN `data_model_2_test_mock` ON\n"
						."\t\t\t`data_model_2_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_2_test_mock_ID`\n"
					."\t\tLEFT OUTER JOIN `data_model_test_mock_related_1ton` ON\n"
						."\t\t\t`data_model_test_mock_related_1ton`.`data_model_test_mock_ID` = `data_model_test_mock`.`ID` AND \n"
						."\t\t\t`data_model_test_mock_related_1ton`.`data_model_test_mock_ID_property` = `data_model_test_mock`.`ID_property`\n"
				."WHERE\n"
					."\t`data_model_2_test_mock`.`string_property`='test' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`='1234' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`>'2' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`<'9999' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`>='3' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`int_property`<='9998' \n"
					."\tAND\n"
					." \t`data_model_test_mock`.`string_property`<>'test' \n"
					."\tOR\n "
					."\t(\n"
						."\t\t`data_model_test_mock`.`ID_property` LIKE 'test%'\n"
						."\t\tAND\n"
						." \t\t`data_model_test_mock`.`int_property`='54321'  \n"
					."\t)\n\n"
				."GROUP BY\n"
					."\tID_property,\n"
					."\tmy_string_property\n\n"
				."HAVING\n"
					."\tmy_count='1234' \n"
					."\tAND\n"
					." \tmy_string_property<>'test' \n"
					."\tOR\n "
					."\t(\n"
						."\t\tmy_string_property LIKE 'test%'\n"
						."\t\tAND\n "
						."\t\tmy_count='54321'  \n"
					."\t)\n";

		//var_dump( $this->object->getBackendCountQuery($query) );
		$this->assertEquals($valid_query, $this->object->getBackendCountQuery($query));
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendInsertQuery
	 */
	public function testGetBackendInsertQuery() {
		$definition = $this->data_model->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( $property_definition->getIsDataModel() ) {
				continue;
			}

			$value = $property_definition->getDefaultValue();
			if($property_name=="ID") {
				$value = "id_value_123";
			}
			$record->addItem($property_definition, $value );
		}

		$valid_query = "INSERT INTO `data_model_test_mock` SET \n"
			."`ID`='id_value_123',\n"
			."`ID_property`='ID default value',\n"
			."`string_property`='default value',\n"
			."`locale_property`='default value',\n"
			."`int_property`=2,\n"
			."`float_property`=2,\n"
			."`bool_property`=1,\n"
			."`array_property`='default value',\n"
			."`date_time_property`='default value',\n"
			."`date_property`='default value'";

		$this->assertEquals($valid_query, $this->object->getBackendInsertQuery($record));
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendUpdateQuery
	 */
	public function testGetBackendUpdateQuery() {

		$query = DataModel_Query::createQuery($this->data_model, $this->where_data);

		$definition = $this->data_model->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( $property_definition->getIsDataModel() ) {
				continue;
			}

			$value = $property_definition->getDefaultValue();
			if($property_name=="ID") {
				$value = "id_value_123";
			}
			$record->addItem($property_definition, $value );
		}

		$valid_query = "UPDATE `data_model_test_mock` SET \n"
			."`ID`='id_value_123',\n"
			."`ID_property`='ID default value',\n"
			."`string_property`='default value',\n"
			."`locale_property`='default value',\n"
			."`int_property`=2,\n"
			."`float_property`=2,\n"
			."`bool_property`=1,\n"
			."`array_property`='default value',\n"
			."`date_time_property`='default value',\n"
			."`date_property`='default value'\n"
		."WHERE\n"
			."\t`data_model_2_test_mock`.`string_property`='test' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`='1234' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`>'2' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`<'9999' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`>='3' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`<='9998' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`string_property`<>'test' \n"
			."\tOR\n "
			."\t(\n"
			."\t\t`data_model_test_mock`.`ID_property` LIKE 'test%'\n"
			."\t\tAND\n"
			." \t\t`data_model_test_mock`.`int_property`='54321'  \n"
			."\t)\n";

		$this->assertEquals($valid_query, $this->object->getBackendUpdateQuery($record, $query) );
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendDeleteQuery
	 */
	public function testGetBackendDeleteQuery() {

		$query = DataModel_Query::createQuery($this->data_model, $this->where_data);

		$valid_query = "DELETE FROM `data_model_test_mock`\n"
			."WHERE\n"
			."\t`data_model_2_test_mock`.`string_property`='test' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`='1234' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`>'2' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`<'9999' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`>='3' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`int_property`<='9998' \n"
			."\tAND\n"
			." \t`data_model_test_mock`.`string_property`<>'test' \n"
			."\tOR\n "
			."\t(\n"
			."\t\t`data_model_test_mock`.`ID_property` LIKE 'test%'\n"
			."\t\tAND\n"
			." \t\t`data_model_test_mock`.`int_property`='54321'  \n"
			."\t)\n";

		$this->assertEquals($valid_query, $this->object->getBackendDeleteQuery($query) );
	}
}
