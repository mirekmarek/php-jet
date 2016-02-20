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
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_Memcache_Config extends Mvc_Router_Cache_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection: '
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Cache_Backend_Memcache_Config', 'getMemcacheConnectionsList']
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select Memcache connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select Memcache connection']
	 *
	 * @var string
	 */
	protected $connection = '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'mvc_c'
	 * @JetConfig:form_field_label = 'Cache key prefix: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify cache key prefix']
	 *
	 * @var string
	 */
	protected $key_prefix = 'mvc_c';

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