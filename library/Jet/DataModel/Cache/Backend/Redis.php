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
	protected $key_prefix = "";


	public function initialize() {
		$this->redis = Redis::get($this->config->getConnection());

		$this->key_prefix = $this->config->getKeyPrefix().":";
	}

	/**
	 * @param DataModel $data_model
	 * @param $ID
	 *
	 * @return string
	 */
	protected function getCacheKey( DataModel $data_model, $ID ) {
		return $this->key_prefix.$data_model->getDataModelName().":".$ID;
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel $data_model, $ID) {
		$data = $this->redis->get( $this->getCacheKey($data_model, $ID) );

		if(!$data) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel $data_model, $ID, $data) {
		$this->redis->set(
				$this->getCacheKey($data_model, $ID),
				serialize($data)
			);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel $data_model, $ID, $data) {

		$this->redis->set(
			$this->getCacheKey($data_model, $ID),
			serialize($data)
		);
	}


	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 */
	public function delete(DataModel $data_model, $ID) {
		$this->redis->delete( $this->getCacheKey($data_model, $ID) );
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		$pattern = $this->key_prefix;
		if($model_name) {
			$pattern .= "{$model_name}:";
		}

		$pattern .= "*";

		$keys = $this->redis->getKeys($pattern);

		foreach( $keys as $key ) {
			$this->redis->delete( $key );
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return "";
	}

	/**
	 *
	 */
	public function helper_create() {
	}

}