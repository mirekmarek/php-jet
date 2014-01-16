<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_ConfigList extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_CONFIG_LIST;
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
	protected $data_path = '';

	/**
	 * @var string
	 */
	protected $config_factory_class_name = '';
	/**
	 * @var string
	 */
	protected $config_factory_method_name = '';

	/**
	 * @var Config_Section[]
	 */
	protected $_configs = array();

	/**
	 * @var string[]
	 */
	protected $_deleted_configs = array();


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
	public function getConfigurationListItem( $name ) {
		$data = $this->_configuration->getData();

		if(isset($this->_configs[$name])) {
			return $this->_configs[$name];
		}

		$config_path = '/'.$this->data_path.'/'.$name;

		if(!$data->exists($config_path)) {

			if($this->_configuration->getSoftMode()) {
				return false;
			}
			throw new Config_Exception(
				'There is not \''.$config_path.'\' section in the config file \''.$this->_configuration->getConfigFilePath().'\'!',
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		/**
		 * @var callable $callback
		 */
		$callback = array( $this->config_factory_class_name, $this->config_factory_method_name );

		$this->_configs[$name] = $callback(
			$data->getRaw($config_path),
			$this->_configuration
		);

		//$this->_adapters[$name]->parseData();

		return $this->_configs[$name];
	}

	/**
	 * @return Config_Section[]
	 *
	 * @throws Config_Exception
	 */
	public function getAllConfigurationItems() {
		$data = $this->_configuration->getData();

		if(!$data->exists($this->data_path)) {
			if($this->_configuration->getSoftMode()) {
				$data->set($this->data_path, array());
			} else {
				throw new Config_Exception(
					'There is not \''.$this->data_path.'\' section in the config file '.$this->_configuration->getConfigFilePath().'!',
					Config_Exception::CODE_CONFIG_CHECK_ERROR
				);
			}
		}

		foreach( array_keys($data->getRaw($this->data_path)) as $name ) {
			$this->getConfigurationListItem($name );
		}


		return $this->_configs;
	}

	/**
	 * @param string $name
	 * @param Config_Section $configuration
	 *
	 */
	public function addConfigurationItem( $name, Config_Section $configuration ) {
		$this->getAllConfigurationItems();

		$this->_configs[$name] = $configuration;
	}

	/**
	 * @param string $name
	 *
	 */
	public function deleteConfigurationItem( $name ) {
		$this->getAllConfigurationItems();

		if(isset($this->_configs[$name])) {
			unset($this->_configs[$name]);
			$this->_deleted_configs[] = $name;
		}
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$this->getAllConfigurationItems();

		$result = array();
		foreach($this->_configs as $name=>$cfg) {
			if(in_array($name, $this->_deleted_configs)) {
				continue;
			}
			$result[$name] = $cfg->toArray();
		}

		return $result;
	}
}