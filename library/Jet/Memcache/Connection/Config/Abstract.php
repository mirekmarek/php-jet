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

abstract class Memcache_Connection_Config_Abstract extends Config_Section {

	/**
	 * @JetConfig:form_field_label = 'Connection name'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:is_required = true
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * @JetConfig:form_field_label = 'Host or socket'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = '127.0.0.1'
	 * @JetConfig:is_required = true
	 * 
	 * @var string
	 */
	protected $host;

	/**
	 * @JetConfig:form_field_label = 'Port'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 11211
	 * @JetConfig:is_required = false
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