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
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = '%JET_DATA_PATH%'
	 * @JetConfig:form_field_label = 'Data directory path: '
	 * 
	 * @var string
	 */
	protected $directory_path = '%JET_DATA_PATH%';
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'database'
	 * @JetConfig:form_field_label = 'Database name: '
	 * 
	 * @var string
	 */
	protected $database_name= 'database';


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

}