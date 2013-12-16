<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Cache
 */
namespace Jet;

abstract class DataModel_Cache_Backend_Abstract extends Object {
	/**
	 * @var null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null
	 */
	protected static $__factory_method_name = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\DataModel_Cache_Backend_Abstract";

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $data_model = null;

	/**
	 * @var DataModel_Cache_Backend_Config_Abstract
	 */
	protected $config;


	/**
	 *
	 * @param DataModel $data_model
	 */
	public function  __construct(  DataModel $data_model ) {
		$this->data_model = $data_model;
		$this->config = $this->data_model->getCacheBackendConfig();

		$this->initialize();
	}

	abstract public function initialize();

	/**
	 * @param string $ID
	 *
	 * @return mixed|null
	 */
	abstract public function get( $ID );

	/**
	 *
	 * @param string $ID
	 * @param mixed $data
	 */
	abstract public function save( $ID, $data );

	/**
	 *
	 * @param string $ID
	 * @param mixed $data
	 */
	abstract public function update( $ID, $data );

	/**
	 * @param string $ID
	 */
	abstract public function delete( $ID );

	/**
	 * @param null|string $model_name (optional)
	 */
	abstract public function truncate( $model_name=null );

	/**
	 * @return string
	 */
	abstract public function helper_getCreateCommand();

	/**
	 *
	 */
	abstract public function helper_create();

}