<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Redis
 */
namespace Jet;

class Redis_Connection_Config extends Config_Section {
	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:is_required = true
     * @JetConfig:form_field_label = 'Connection name'
     * @JetConfig:form_field_error_messages = ['empty'=>'Please specify connection name']
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = '127.0.0.1'
	 * @JetConfig:is_required = true
     * @JetConfig:form_field_label = 'Host or socket'
     * @JetConfig:form_field_error_messages = ['empty'=>'Please specify Redis server host or socket']
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 6379
	 * @JetConfig:is_required = false
     * @JetConfig:form_field_label = 'Port'
     * @JetConfig:form_field_error_messages = ['empty'=>'Please specify Redis server port']
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