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

class DataModel_Cache_Backend_Redis extends DataModel_Cache_Backend_Abstract {

	/**
	 * @var DataModel_Cache_Backend_Redis_Config
	 */
	protected $config;

	/**
	 *
	 * @var Redis_Connection_Abstract
	 */
	private $redis = null;

	/**
	 * @var string
	 */
	protected $key_prefix = '';


	public function initialize() {
		$this->redis = Redis::get($this->config->getConnection());

		$this->key_prefix = $this->config->getKeyPrefix().':';
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param $ID
	 *
	 * @return string
	 */
	protected function getCacheKey( DataModel_Definition_Model_Abstract $data_model_definition, $ID ) {
		return $this->key_prefix.$data_model_definition->getModelName().':'.$ID;
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel_Definition_Model_Abstract $data_model_definition, $ID) {
		$data = $this->redis->get( $this->getCacheKey($data_model_definition, $ID) );

		if(!$data) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {
		$this->redis->set(
				$this->getCacheKey($data_model_definition, $ID),
				serialize($data)
			);
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {

		$this->redis->set(
			$this->getCacheKey($data_model_definition, $ID),
			serialize($data)
		);
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 */
	public function delete(DataModel_Definition_Model_Abstract $data_model_definition, $ID) {
		$this->redis->delete( $this->getCacheKey($data_model_definition, $ID) );
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		$pattern = $this->key_prefix;
		if($model_name) {
			$pattern .= $model_name.':';
		}

		$pattern .= '*';

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		$keys = $this->redis->getKeys($pattern);

		foreach( $keys as $key ) {
			$this->redis->delete( $key );
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return '';
	}

	/**
	 *
	 */
	public function helper_create() {
	}

}