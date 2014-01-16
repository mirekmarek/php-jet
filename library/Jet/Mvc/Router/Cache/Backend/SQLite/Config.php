<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_SQLite_Config extends Mvc_Router_Cache_Backend_Config_Abstract {

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		'directory_path' => array(
			'type' => self::TYPE_STRING,
			'is_required' => true,
			'default_value' => '%JET_DATA_PATH%',
			'form_field_label' => 'Data directory path: ',
		),
		'database_name' => array(
			'type' => self::TYPE_STRING,
			'is_required' => true,
			'default_value' => 'mvc_router_cache',
			'form_field_label' => 'Database name: ',
		),
		'table_name' => array(
			'type' => self::TYPE_STRING,
			'is_required' => false,
			'default_value' => 'jet_mvc_router_cache',
			'form_field_label' => 'Table name: ',
		),
	);

	/**
	 * @var string
	 */
	protected $directory_path = '%JET_DATA_PATH%';
	/**
	 * @var string
	 */
	protected $database_name= 'mvc_router_cache';

	/**
	 * @var string
	 */
	protected $table_name = '';


	/**
	 * @return string
	 */
	public function getDirectorypath() {
		return Data_Text::replaceSystemConstants( $this->directory_path );
	}

	/**
	 * @return string
	 */
	public function getDatabaseName() {
		return $this->database_name;
	}

	/**
	 * @return string
	 */
	public function getDSN() {
		$dp = $this->getDirectorypath();

		if($dp==':memory:') {
			return $dp;
		}
		return $dp.$this->getDatabaseName().'.sq3';
	}


	/**
	 * @return string
	 */
	public function getTableName() {
		return $this->table_name;
	}

}