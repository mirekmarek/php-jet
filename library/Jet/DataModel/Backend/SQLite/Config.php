<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_Backend_SQLite_Config extends DataModel_Backend_Config_Abstract {

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"directory_path" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"default_value" => "%JET_DATA_PATH%",
			"form_field_label" => "Data directory path: ",
		),
		"database_name" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"default_value" => "database",
			"form_field_label" => "Database name: ",
		),
	);

	/**
	 * @var string
	 */
	protected $directory_path = "%JET_DATA_PATH%";
	/**
	 * @var string
	 */
	protected $database_name= "";


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
		return $this->getDirectorypath().$this->getDatabaseName().".sq3";
	}

}