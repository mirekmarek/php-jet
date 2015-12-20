<?php
namespace Jet;

require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModelRelated1TONTestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModelRelated1TO1TestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModelRelatedMTONTestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModel2TestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModel2Related1TONTestMock.php';

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
		$this->config->setData( array(
							'connection_read' => 'test_mysql',
							'connection_write' => 'test_mysql',
					), false
				);

		$this->object = new DataModel_Backend_MySQL( $this->config );
		$this->object->initialize();

		$this->data_model = new DataModel_Query_DataModelTestMock();
		$this->data_model->setBackendType('MySQL');
		$this->data_model->setBackendOptions( array(
			'connection_read' => 'test_mysql',
			'connection_write' => 'test_mysql',
		) );

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->select_data = array(
			$this->properties['ID_property'],
			'my_string_property' => $this->properties['string_property'],
			'my_sum' => [
				[
					$this->properties['int_property'],
					$this->properties['float_property']
				],
				'SUM(%int_property%)+%float_property%'
			],
			'my_count' => [
				'this.int_property',
				'COUNT(%int_property%)'
			],
			'data_model_test_mock_related_1toN.string_property'
		);

		$this->where_data = array(
			'data_model_2_test_mock.string_property' => 'test',
			'AND',
			'this.int_property =' => 1234,
			'AND',
			'this.int_property >' => 2,
			'AND',
			'this.int_property <' => 9999,
			'AND',
			'this.int_property >=' => 3,
			'AND',
			'this.int_property <=' => 9998,
			'AND',
			'this.string_property !=' => 'test',
			'OR',
			[
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			]
		);

		$this->having_data = array(
			'my_count =' => 1234,
			'AND',
			'my_string_property !=' => 'test',
			'OR',
			[
				'my_string_property *' => 'test%',
				'AND',
				'my_count' => 54321
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

		$valid_q = 'CREATE TABLE IF NOT EXISTS `data_model_test_mock` ('.JET_EOL
				.JET_TAB.'`ID` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT \'\','.JET_EOL
				.JET_TAB.'`ID_property` varchar(50) COLLATE utf8_bin NOT NULL  DEFAULT \'\','.JET_EOL
				.JET_TAB.'`string_property` varchar(123) DEFAULT \'default value\','.JET_EOL
				.JET_TAB.'`locale_property` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT \'default value\','.JET_EOL
				.JET_TAB.'`int_property` int DEFAULT 2,'.JET_EOL
				.JET_TAB.'`float_property` float DEFAULT 2.2,'.JET_EOL
				.JET_TAB.'`bool_property` tinyint(1) DEFAULT 1,'.JET_EOL
				.JET_TAB.'`array_property` longtext,'.JET_EOL
				.JET_TAB.'`date_time_property` datetime DEFAULT NULL,'.JET_EOL
				.JET_TAB.'`date_property` date DEFAULT NULL'.JET_EOL
				.JET_TAB.',PRIMARY KEY (`ID`, `ID_property`)'.JET_EOL
				.') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'.JET_EOL.JET_EOL;

		$this->assertEquals($valid_q, $q);
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::helper_getDropCommand
	 */
	public function testHelper_getDropCommand() {
		$data_model = new DataModel_Query_DataModelTestMock();

		$valid_q = 'RENAME TABLE `data_model_test_mock` TO `_d'.date('YmdHis').'data_model_test_mock`';

		$q = $this->object->helper_getDropCommand( $data_model );

		$this->assertEquals($valid_q, $q);
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::helper_getUpdateCommand
	 */
	public function testHelper_getUpdateCommand() {
		$pdo = Db::get('test_mysql');

		$pdo->execCommand('CREATE TABLE IF NOT EXISTS `data_model_test_mock` (
							`ID` varchar(50) COLLATE utf8_bin NOT NULL,
					        `ID_property` varchar(50) COLLATE utf8_bin NOT NULL,
					        `string_property` varchar(123),
					        `locale_property` varchar(20) COLLATE utf8_bin NOT NULL,
					        `float_property` float,
					        `bool_property` tinyint(1),
					        `array_property` longtext,
					        `date_time_property` datetime
					        ,PRIMARY KEY (`ID`, `ID_property`)
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;');


		$timestamp = date('YmdHis');

		$valid_d = array (
			0 => 'CREATE TABLE IF NOT EXISTS `_UP'.$timestamp.'_data_model_test_mock` ('.JET_EOL
                    .JET_TAB.'`ID` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT \'\','.JET_EOL
                    .JET_TAB.'`ID_property` varchar(50) COLLATE utf8_bin NOT NULL  DEFAULT \'\','.JET_EOL
                    .JET_TAB.'`string_property` varchar(123) DEFAULT \'default value\','.JET_EOL
                    .JET_TAB.'`locale_property` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT \'default value\','.JET_EOL
                    .JET_TAB.'`int_property` int DEFAULT 2,'.JET_EOL
                    .JET_TAB.'`float_property` float DEFAULT 2.2,'.JET_EOL
                    .JET_TAB.'`bool_property` tinyint(1) DEFAULT 1,'.JET_EOL
                    .JET_TAB.'`array_property` longtext,'.JET_EOL
                    .JET_TAB.'`date_time_property` datetime DEFAULT NULL,'.JET_EOL
                    .JET_TAB.'`date_property` date DEFAULT NULL'.JET_EOL
                    .JET_TAB.',PRIMARY KEY (`ID`, `ID_property`)'.JET_EOL
                    .') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'.JET_EOL.JET_EOL,

			1 => 'INSERT INTO `_UP'.$timestamp.'_data_model_test_mock` (`ID`,`ID_property`,`string_property`,`locale_property`,`float_property`,`bool_property`,`array_property`,`date_time_property`) SELECT `ID`,`ID_property`,`string_property`,`locale_property`,`float_property`,`bool_property`,`array_property`,`date_time_property` FROM `data_model_test_mock`;',
			2 => 'UPDATE `_UP'.$timestamp.'_data_model_test_mock` SET `int_property`=2, `date_property`=\'default value\'',
			3 => 'RENAME TABLE `data_model_test_mock` TO `_UP'.$timestamp.'_b_data_model_test_mock`;',
			4 => 'RENAME TABLE `_UP'.$timestamp.'_data_model_test_mock` TO  `data_model_test_mock`;',
		);

		$this->assertEquals( $valid_d, $this->object->helper_getUpdateCommand( $this->data_model ) );

		$pdo->execCommand('DROP TABLE IF EXISTS data_model_test_mock');
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendSelectQuery
	 */
	public function testGetBackendSelectQuery() {

		$query = DataModel_Query::createQuery($this->data_model->getDataModelDefinition(), $this->where_data);
		$query->setSelect($this->select_data)
			->setRelationJoinType('data_model_test_mock_related_MtoN', DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setRelationJoinType('data_model_test_mock_related_1toN', DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setHaving($this->having_data)
			->setOrderBy( array('+my_string_property', '-my_count', 'this.int_property') )
			->setGroupBy( array('ID_property', 'my_string_property', 'this.int_property') )
			->setLimit(100, 10);

		$valid_query =
				'SELECT'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`ID_property` AS `ID_property`,'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`string_property` AS `my_string_property`,'.JET_EOL
					.JET_TAB.'SUM(`data_model_test_mock`.`int_property`)+`data_model_test_mock`.`float_property` AS `my_sum`,'.JET_EOL
					.JET_TAB.'COUNT(`data_model_test_mock`.`int_property`) AS `my_count`,'.JET_EOL
					.JET_TAB.'`data_model_test_mock_related_1ton`.`string_property` AS `string_property`'.JET_EOL
				.'FROM'.JET_EOL
					.JET_TAB.'`data_model_test_mock`'.JET_EOL
						.JET_TAB.JET_TAB.'LEFT OUTER JOIN `data_model_test_mock_related_mton` ON'.JET_EOL
							.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_test_mock_ID` AND'.JET_EOL
							.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` = `data_model_test_mock_related_mton`.`data_model_test_mock_ID_property`'.JET_EOL
						.JET_TAB.JET_TAB.'JOIN `data_model_2_test_mock` ON'.JET_EOL
							.JET_TAB.JET_TAB.JET_TAB.'`data_model_2_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_2_test_mock_ID`'.JET_EOL
						.JET_TAB.JET_TAB.'LEFT OUTER JOIN `data_model_test_mock_related_1ton` ON'.JET_EOL
							.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID` = `data_model_test_mock_related_1ton`.`main_ID` AND'.JET_EOL
							.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` = `data_model_test_mock_related_1ton`.`main_ID_property`'.JET_EOL
				.'WHERE'.JET_EOL
					.JET_TAB.'`data_model_2_test_mock`.`string_property`=\'test\''.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`=1234'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`>2'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`<9999'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`>=3'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`<=9998'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`string_property`<>\'test\''.JET_EOL
					.JET_TAB.'OR'.JET_EOL
					.JET_TAB.'('.JET_EOL
						.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` LIKE \'test%\''.JET_EOL
						.JET_TAB.JET_TAB.'AND'.JET_EOL
						.JET_TAB.JET_TAB.'`data_model_test_mock`.`int_property`=54321'.JET_EOL
						.JET_TAB.')'.JET_EOL.JET_EOL
				.'GROUP BY'.JET_EOL
					.JET_TAB.'ID_property,'.JET_EOL
					.JET_TAB.'my_string_property,'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`'.JET_EOL.JET_EOL
				.'HAVING'.JET_EOL
					.JET_TAB.'my_count=1234'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'my_string_property<>\'test\''.JET_EOL
					.JET_TAB.'OR'.JET_EOL
					.JET_TAB.'('.JET_EOL
						.JET_TAB.JET_TAB.'my_string_property LIKE \'test%\''.JET_EOL
						.JET_TAB.JET_TAB.'AND'.JET_EOL
						.JET_TAB.JET_TAB.'my_count=54321'.JET_EOL
					.JET_TAB.')'.JET_EOL.JET_EOL
				.'ORDER BY'.JET_EOL
					.JET_TAB.'my_string_property ASC,'.JET_EOL
					.JET_TAB.'my_count DESC,'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property` ASC'.JET_EOL.JET_EOL
				.'LIMIT 10,100'.JET_EOL;


		$this->assertEquals($valid_query, $this->object->getBackendSelectQuery( $query));
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendCountQuery
	 */
	public function testGetBackendCountQuery() {
		$query = DataModel_Query::createQuery($this->data_model->getDataModelDefinition(), $this->where_data);
		$query->setSelect($this->select_data)
			->setRelationJoinType('data_model_test_mock_related_MtoN', DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setRelationJoinType('data_model_test_mock_related_1toN', DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN)
			->setHaving($this->having_data)
			->setOrderBy( array('+my_string_property', '-my_count') )
			->setGroupBy( array('ID_property', 'my_string_property') )
			->setLimit(100, 10);


		$valid_query = 'SELECT count(*) FROM'.JET_EOL
					.JET_TAB.'`data_model_test_mock`'.JET_EOL
						.JET_TAB.JET_TAB.'LEFT OUTER JOIN `data_model_test_mock_related_mton` ON'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_test_mock_ID` AND'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` = `data_model_test_mock_related_mton`.`data_model_test_mock_ID_property`'.JET_EOL
						.JET_TAB.JET_TAB.'JOIN `data_model_2_test_mock` ON'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.'`data_model_2_test_mock`.`ID` = `data_model_test_mock_related_mton`.`data_model_2_test_mock_ID`'.JET_EOL
						.JET_TAB.JET_TAB.'LEFT OUTER JOIN `data_model_test_mock_related_1ton` ON'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID` = `data_model_test_mock_related_1ton`.`main_ID` AND'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` = `data_model_test_mock_related_1ton`.`main_ID_property`'.JET_EOL
				.'WHERE'.JET_EOL
					.JET_TAB.'`data_model_2_test_mock`.`string_property`=\'test\''.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`=1234'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`>2'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`<9999'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`>=3'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`int_property`<=9998'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'`data_model_test_mock`.`string_property`<>\'test\''.JET_EOL
					.JET_TAB.'OR'.JET_EOL
					.JET_TAB.'('.JET_EOL
						.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` LIKE \'test%\''.JET_EOL
						.JET_TAB.JET_TAB.'AND'.JET_EOL
						.JET_TAB.JET_TAB.'`data_model_test_mock`.`int_property`=54321'.JET_EOL
					.JET_TAB.')'.JET_EOL.JET_EOL
				.'GROUP BY'.JET_EOL
					.JET_TAB.'ID_property,'.JET_EOL
					.JET_TAB.'my_string_property'.JET_EOL.JET_EOL
				.'HAVING'.JET_EOL
					.JET_TAB.'my_count=1234'.JET_EOL
					.JET_TAB.'AND'.JET_EOL
					.JET_TAB.'my_string_property<>\'test\''.JET_EOL
					.JET_TAB.'OR'.JET_EOL
					.JET_TAB.'('.JET_EOL
						.JET_TAB.JET_TAB.'my_string_property LIKE \'test%\''.JET_EOL
						.JET_TAB.JET_TAB.'AND'.JET_EOL
						.JET_TAB.JET_TAB.'my_count=54321'.JET_EOL
					.JET_TAB.')'.JET_EOL;

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
			if( !$property_definition->getCanBeInInsertRecord() ) {
				continue;
			}

			$value = $property_definition->getDefaultValue( $this->data_model );
			if($property_name=='ID') {
				$value = 'id_value_123';
			}
			$record->addItem($property_definition, $value );
		}

		$valid_query = 'INSERT INTO `data_model_test_mock` SET '.JET_EOL
			.'`ID`=\'id_value_123\','.JET_EOL
			.'`ID_property`=\'ID default value\','.JET_EOL
			.'`string_property`=\'default value\','.JET_EOL
			.'`locale_property`=\'default value\','.JET_EOL
			.'`int_property`=2,'.JET_EOL
			.'`float_property`=2.2,'.JET_EOL
			.'`bool_property`=1,'.JET_EOL
			.'`array_property`=\'default value\','.JET_EOL
			.'`date_time_property`=\'default value\','.JET_EOL
			.'`date_property`=\'default value\'';

		$this->assertEquals($valid_query, $this->object->getBackendInsertQuery($record));
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendUpdateQuery
	 */
	public function testGetBackendUpdateQuery() {

		$query = DataModel_Query::createQuery($this->data_model->getDataModelDefinition(), $this->where_data);

		$definition = $this->data_model->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( !$property_definition->getCanBeInUpdateRecord() ) {
				continue;
			}

			$value = $property_definition->getDefaultValue( $this->data_model );
			if($property_name=='ID') {
				$value = 'id_value_123';
			}
			$record->addItem($property_definition, $value );
		}

		$valid_query = 'UPDATE `data_model_test_mock` SET '.JET_EOL
			.'`string_property`=\'default value\','.JET_EOL
			.'`locale_property`=\'default value\','.JET_EOL
			.'`int_property`=2,'.JET_EOL
			.'`float_property`=2.2,'.JET_EOL
			.'`bool_property`=1,'.JET_EOL
			.'`array_property`=\'default value\','.JET_EOL
			.'`date_time_property`=\'default value\','.JET_EOL
			.'`date_property`=\'default value\''.JET_EOL
		.'WHERE'.JET_EOL
			.JET_TAB.'`data_model_2_test_mock`.`string_property`=\'test\''.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`=1234'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`>2'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`<9999'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`>=3'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`<=9998'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`string_property`<>\'test\''.JET_EOL
			.JET_TAB.'OR'.JET_EOL
			.JET_TAB.'('.JET_EOL
			.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` LIKE \'test%\''.JET_EOL
			.JET_TAB.JET_TAB.'AND'.JET_EOL
			.JET_TAB.JET_TAB.'`data_model_test_mock`.`int_property`=54321'.JET_EOL
			.JET_TAB.')'.JET_EOL;

		$this->assertEquals($valid_query, $this->object->getBackendUpdateQuery($record, $query) );
	}

	/**
	 * @covers Jet\DataModel_Backend_MySQL::getBackendDeleteQuery
	 */
	public function testGetBackendDeleteQuery() {

		$query = DataModel_Query::createQuery($this->data_model->getDataModelDefinition(), $this->where_data);

		$valid_query = 'DELETE FROM `data_model_test_mock`'.JET_EOL
			.'WHERE'.JET_EOL
			.JET_TAB.'`data_model_2_test_mock`.`string_property`=\'test\''.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`=1234'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`>2'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`<9999'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`>=3'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`int_property`<=9998'.JET_EOL
			.JET_TAB.'AND'.JET_EOL
			.JET_TAB.'`data_model_test_mock`.`string_property`<>\'test\''.JET_EOL
			.JET_TAB.'OR'.JET_EOL
			.JET_TAB.'('.JET_EOL
			.JET_TAB.JET_TAB.'`data_model_test_mock`.`ID_property` LIKE \'test%\''.JET_EOL
			.JET_TAB.JET_TAB.'AND'.JET_EOL
			.JET_TAB.JET_TAB.'`data_model_test_mock`.`int_property`=54321'.JET_EOL
			.JET_TAB.')'.JET_EOL;


		$this->assertEquals($valid_query, $this->object->getBackendDeleteQuery($query) );
	}
}
