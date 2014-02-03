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
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_History
 */
namespace Jet;

/**
 * Class DataModel_History_Backend_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\DataModel_History_Backend_Abstract'
 */
abstract class DataModel_History_Backend_Abstract extends Object {

	const OPERATION_SAVE = 'save';
	const OPERATION_UPDATE = 'update';
	const OPERATION_DELETE = 'delete';

	/**
	 * @var DataModel_History_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 * @var null|string
	 */
	protected $_current_operation_ID = null;

	/**
	 * @var DataModel
	 */
	protected $_current_data_model = null;

	/**
	 *
	 * @param DataModel_History_Backend_Config_Abstract $config
	 *
	 */
	public function  __construct( DataModel_History_Backend_Config_Abstract $config ) {
		$this->config = $config;

		$this->initialize();
	}

	/**
	 *
	 */
	abstract function initialize();

	/**
	 * @param DataModel $data_model
	 * @param string $operation
	 *
	 * @return
	 */
	abstract function operationStart( DataModel $data_model, $operation );

	/**
	 *
	 */
	abstract function operationDone();

	/**
	 * @return string
	 */
	abstract function helper_getCreateCommand();

	/**
	 *
	 */
	abstract function helper_create();

	/**
	 * @return string
	 */
	protected function generateOperationID() {
		$time = floor(microtime(true) * 1000);

		$unique_ID = uniqid('', true);

		$u_name = substr(php_uname('n'), 0,14);

		$ID = $u_name.$time .$unique_ID;

		$ID = substr( preg_replace('~[^a-zA-Z0-9]~', '_', $ID), 0, 50);

		return $ID;

	}

}