<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

class Mvc_Router_Map_Cache_Backend_Redis_Config extends Mvc_Router_Map_Cache_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Map_Cache_Backend_Redis_Config', 'getRedisConnectionsList']
	 * 
	 * @var string
	 */
	protected $connection = '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'mvc_map'
	 * @JetConfig:form_field_label = 'Cache key: '
	 * 
	 * @var string
	 */
	protected $key = 'mvc_map';

	/**
	 * @return string
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getRedisConnectionsList() {
		return Redis_Config::getConnectionsList();
	}

}