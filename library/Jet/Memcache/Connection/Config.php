<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Memcache
 */
namespace Jet;

class Memcache_Connection_Config extends Config_Section {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:is_required = true
     * @JetConfig:form_field_label = 'Connection name'
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify connection name']
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = '127.0.0.1'
	 * @JetConfig:is_required = true
     * @JetConfig:form_field_label = 'Host or socket'
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify Memcache server host or socket']
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 11211
	 * @JetConfig:is_required = false
     * @JetConfig:form_field_label = 'Port'
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify Memcache server port']
	 *
	 * @var string
	 */
	protected $port;


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 *
	 * @return string
	 */
	public function getPort() {
		return $this->port;
	}
}