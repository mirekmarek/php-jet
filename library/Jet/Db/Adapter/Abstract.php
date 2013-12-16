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

abstract class Db_Adapter_Abstract extends Object {
	/**
	 * @var null|string
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_class_method = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Db_Adapter_Abstract";



	/**
	 *
	 * @var Db_Adapter_Config_Abstract
	 */
	protected $config = null;

	/**
	 * @param Db_Adapter_Config_Abstract $config
	 */
	public function __construct( Db_Adapter_Config_Abstract $config ) {
		$this->config = $config;
	}

	/**
	 *
	 * @return Db_Adapter_Config_Abstract
	 */
	public function getConfig(){
		return $this->config;
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
				$value = "NULL";
			}

			if($value instanceof Db_Expr){
				$value = (string)$value;
			}

			if(is_string($value)){
				$value = $this->quote($value);
			}


			if(is_bool($value)){
				$value = $value ? 1 : 0;
			}

			if(is_int($value) || is_float($value)){
			}

			$replacements[":{$key}"] = $value;
		}

		krsort($replacements, SORT_STRING);

		return str_replace(
			array_keys($replacements),
			array_values($replacements),
			$query
		);

	}

	/**
	 * Executes query and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	abstract public function query($query, array $query_data = array());

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
	 * @abstract
	 *
	 * @param string $name (optional)
	 *
	 * @return mixed
	 */
	abstract public function lastInsertId( $name = null );

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	abstract public function quote($string);

		/**
	 * Begin a transaction.
	 */
	abstract public function beginTransaction();

	/**
	 * Roll back a transaction
	 *
	 */
	abstract public function rollBack();

	/**
	 * Commit a transaction
	 */
	abstract public function commit();

	/**
	 *
	 */
	abstract public function disconnect();


}