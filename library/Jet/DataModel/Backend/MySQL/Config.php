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
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_Backend_MySQL_Config extends DataModel_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection - read: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
	 * 
	 * @var string
	 */
	protected $connection_read = '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection - write: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
	 * 
	 * @var string
	 */
	protected $connection_write= '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'InnoDB'
	 * @JetConfig:form_field_label = 'Engine: '
	 * 
	 * @var string
	 */
	protected $engine= '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'utf8'
	 * @JetConfig:form_field_label = 'Default charset: '
	 * 
	 * @var string
	 */
	protected $default_charset= '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'utf8_general_ci'
	 * @JetConfig:form_field_label = 'Default collate: '
	 * 
	 * @var string
	 */
	protected $collate= '';


	/**
	 * @return string
	 */
	public function getCollate() {
		return $this->collate;
	}

	/**
	 * @return string
	 */
	public function getConnectionRead() {
		return $this->connection_read;
	}

	/**
	 * @return string
	 */
	public function getConnectionWrite() {
		return $this->connection_write;
	}

	/**
	 * @return string
	 */
	public function getDefaultCharset() {
		return $this->default_charset;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @return array
	 */
	public static function getDbConnectionsList() {
		return Db_Config::getConnectionsList(Db::DRIVER_MYSQL);
	}
}