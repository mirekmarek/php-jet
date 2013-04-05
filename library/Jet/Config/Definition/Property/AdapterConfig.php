<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_AdapterConfig extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_ADAPTER_CONFIG;
	/**
	 * @var bool
	 */
	protected $_is_array = true;

	/**
	 * @var array
	 */
	protected $default_value = array();

	/**
	 * @var string
	 */
	protected $item_type;

	/**
	 * @var string|bool
	 */
	protected $form_field_type = false;


	/**
	 * @var string
	 */
	protected $data_path = "";
	/**
	 * @var string
	 */
	protected $adapter_type_key = "";
	/**
	 * @var string
	 */
	protected $config_factory_class_name = "";
	/**
	 * @var string
	 */
	protected $config_factory_method_name = "";

	/**
	 * @var Config_Section[]
	 */
	protected $_adapters = array();

	/**
	 * @var string[]
	 */
	protected $_deleted_adapters = array();


	/**
	 * @param array|null $definition_data
	 * 
	 * @throws Config_Exception
	 */
	public function setUp(array $definition_data = null ) {
		if(!$definition_data) {
			return;
		}

		parent::setUp($definition_data);
	}

	/**
	 * Do nothing
	 *
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
	}

	/**
	 * @param string $name
	 *
	 * @return bool|Config_Section
	 *
	 * @throws Config_Exception
	 */
	public function getAdapterConfiguration( $name ) {
		$data = $this->_configuration->getData();

		if(isset($this->_adapters[$name])) {
			return $this->_adapters[$name];
		}

		if(!$data->exists($this->data_path)) {
			if($this->_configuration->getSoftMode()) {
				return false;
			}
			throw new Config_Exception(
				"There is not '{$this->data_path}' section in the config file '".$this->_configuration->getConfigFilePath()."'!",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		$config_path = "/{$this->data_path}/{$name}";
		$type_path = $config_path."/{$this->adapter_type_key}";

		if(!$data->exists($config_path)) {
			return false;
		}

		if(!$data->exists($type_path)) {
			throw new Config_Exception(
				"Adapter type is not specified! There is not '{$type_path}' value in the '{$name}' configuration. Config file '".$this->_configuration->getConfigFilePath()."'",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}
		/**
		 * @var callable $callback
		 */
		$callback = array( $this->config_factory_class_name, $this->config_factory_method_name );

		$this->_adapters[$name] = $callback(
			$this->_configuration,
			$data->getString($type_path),
			$data->getRaw($config_path)
		);

		//$this->_adapters[$name]->parseData();

		return $this->_adapters[$name];
	}

	/**
	 * @return Config_Section[]
	 *
	 * @throws Config_Exception
	 */
	public function getAllAdaptersConfiguration() {
		$data = $this->_configuration->getData();

		if(!$data->exists($this->data_path)) {
			if($this->_configuration->getSoftMode()) {
				$data->set($this->data_path, array());
			} else {
				throw new Config_Exception(
					"There is not '{$this->data_path}' section in the config file ".$this->_configuration->getConfigFilePath()."!",
					Config_Exception::CODE_CONFIG_CHECK_ERROR
				);
			}
		}

		foreach( array_keys($data->getRaw($this->data_path)) as $name ) {
			$this->getAdapterConfiguration($name );
		}


		return $this->_adapters;
	}

	/**
	 * @param string $name
	 * @param Config_Section $configuration
	 *
	 */
	public function addAdapterConfiguration( $name, Config_Section $configuration ) {
		$this->getAllAdaptersConfiguration();

		$this->_adapters[$name] = $configuration;
	}

	/**
	 * @param string $name
	 *
	 */
	public function deleteAdapterConfiguration( $name ) {
		$this->getAllAdaptersConfiguration();

		if(isset($this->_adapters[$name])) {
			unset($this->_adapters[$name]);
			$this->_deleted_adapters[] = $name;
		}
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$this->getAllAdaptersConfiguration();

		$result = array();
		foreach($this->_adapters as $name=>$cfg) {
			if(in_array($name, $this->_deleted_adapters)) {
				continue;
			}
			$result[$name] = $cfg->toArray();
		}

		return $result;
	}
}