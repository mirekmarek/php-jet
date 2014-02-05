<?php
/**
 *
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
 * @package DataModel
 * @subpackage DataModel_History
 */
namespace Jet;

class DataModel_History_Backend_SQLite_Config extends DataModel_History_Backend_Config_Abstract {

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
	 * @JetConfig:default_value = 'datamodel_history'
	 * @JetConfig:form_field_label = 'Database name: '
	 *
	 * @var string
	 */
	protected $database_name= 'datamodel_history';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = 'jet_datamodel_history'
	 * @JetConfig:form_field_label = 'Table name: '
	 * 
	 * @var string
	 */
	protected $table_name = '';


	/**
	 * @return string
	 */
	public function getDirectoryPath() {
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
		$dp = $this->getDirectoryPath();

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