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

/**
 * Class DataModel_Cache_Backend_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\DataModel_Cache_Backend_Abstract'
 */
abstract class DataModel_Cache_Backend_Abstract extends Object {

	/**
	 * @var DataModel_Cache_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 *
	 * @param DataModel_Cache_Backend_Config_Abstract $config
	 *
	 */
	public function  __construct(  DataModel_Cache_Backend_Config_Abstract $config ) {
		$this->config = $config;

		$this->initialize();
	}

	abstract public function initialize();

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @return mixed|null
	 */
	abstract public function get( DataModel $data_model, $ID );

	/**
	 *
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @param mixed $data
	 */
	abstract public function save( DataModel $data_model, $ID, $data );

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @param mixed $data
	 */
	abstract public function update( DataModel $data_model, $ID, $data );

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 */
	abstract public function delete( DataModel $data_model, $ID );

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