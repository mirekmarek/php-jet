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
 * @package DataModel
 * @subpackage DataModel_Cache
 */
namespace Jet;

class DataModel_Cache_Backend_Memcache_Config extends DataModel_Cache_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Cache_Backend_Redis_Config', 'getRedisConnectionsList']
     * @JetConfig:form_field_label = 'Connection: '
     * @JetConfig:form_field_error_messages = ['empty'=>'Please select Memcache connection', 'invalid_value'=>'Please select Memcache connection']
	 *
	 * @var string
	 */
	protected $connection = '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'do_c'
	 * @JetConfig:form_field_label = 'Cache key prefix: '
     * @JetConfig:form_field_error_messages = ['empty'=>'Please specify cache key prefix']
	 *
	 * @var string
	 */
	protected $key_prefix = 'do_c';

	/**
	 * @return string
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @return string
	 */
	public function getKeyPrefix() {
		return $this->key_prefix;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getMemcacheConnectionsList() {
		return Memcache_Config::getConnectionsList();
	}

}