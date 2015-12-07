<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

/**
 * Class Db_Connection_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\Db_Connection_Abstract'
 */
abstract class Db_Connection_Abstract extends \PDO implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicGet;
	use Object_Trait_MagicSet;
	use Object_Trait_MagicClone;

	/**
	 *
	 * @var Db_Connection_Config_Abstract
	 */
	protected $config = null;

	/**
	 * @param Db_Connection_Config_Abstract $config
	 */
	public function __construct( Db_Connection_Config_Abstract $config ) {

		$this->config = $config;

		parent::__construct( $config->getDsn(), $config->getUsername(), $config->getPassword() );

		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	}

	/**
	 * Close connection on exit
	 */
	public function __destruct() {
		try {
			$this->disconnect();
		} catch(Exception $e){}
	}

	/**
	 *
	 * @return Db_Connection_Config_Abstract
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return string
	 */
	public function prepareQuery($query, array $query_data=array()) {

		if(!$query_data){
			return $query;
		}


		$replacements = array();

		foreach($query_data as $key => $value){

			if($value === null){
				$value = 'NULL';
			} else
			if(is_bool($value)){
				$value = $value ? 1 : 0;
			} else
			if(is_int($value) || is_float($value)){

			} else {
				$value = $this->quote((string)$value);

			}

			$replacements[':'.$key] = $value;
		}

		krsort($replacements, SORT_STRING);

		return str_replace(
			array_keys($replacements),
			array_values($replacements),
			$query
		);

	}

	/**
	 * Executes command (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	abstract public function execCommand($query, array $query_data = array());

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @return array
	 */
	abstract public function fetchAll($query, array $query_data = array());

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 *
	 * @return array|bool
	 */
	abstract public function fetchRow($query, array $query_data = array());

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $key_column (optional)
	 *
	 * @return array
	 */
	abstract public function fetchAssoc($query, array $query_data = array(), $key_column = null);

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $column (optional, default: 1st column)
	 *
	 * @return array
	 */
	abstract function fetchCol($query, array $query_data = array(), $column = null);

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $key_column (optional, default: 1st column)
	 * @param string $value_column (optional, default: 2nd column)
	 *
	 * @return array
	 */
	abstract public function fetchPairs($query, array $query_data = array(), $key_column = null, $value_column = null);

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $column (optional, default:1st column)
	 *
	 * @return mixed
	 */
	abstract public function fetchOne($query, array $query_data = array(), $column = null);


	/**
	 *
	 */
	abstract public function disconnect();

}