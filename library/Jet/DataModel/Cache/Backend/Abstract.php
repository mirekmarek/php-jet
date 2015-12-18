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
 * @JetFactory:mandatory_parent_class = 'DataModel_Cache_Backend_Abstract'
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
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @return mixed|null
	 */
	abstract public function get( DataModel_Definition_Model_Abstract $data_model_definition, $ID );

	/**
	 *
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @param mixed $data
	 */
	abstract public function save( DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data );

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @param mixed $data
	 */
	abstract public function update( DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data );

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 */
	abstract public function delete( DataModel_Definition_Model_Abstract $data_model_definition, $ID );

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