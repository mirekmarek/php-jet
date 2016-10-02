<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 */
namespace Jet;

trait DataModel_Trait_Backend {

	/**
	 * @var bool
	 */
	private $_backend_transaction_started = false;

    /**
     * Returns backend instance
     *
     * @return DataModel_Backend_Abstract
     */
    public static function getBackendInstance() {
        /** @noinspection PhpUndefinedMethodInspection */
        return static::getDataModelDefinition()->getBackendInstance();
    }


    /**
     * @return bool
     */
    public function getBackendTransactionStarted() {

        return $this->_backend_transaction_started;
    }

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance() {
        return $this->_backend_transaction_started;
    }

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function startBackendTransaction( DataModel_Backend_Abstract $backend ) {
        /**
         * @var DataModel $this
         */
        if(!$this->getBackendTransactionStarted()) {
            $this->_backend_transaction_started = true;

            $backend->transactionStart();
        }
    }

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function commitBackendTransaction( DataModel_Backend_Abstract $backend ) {
        /**
         * @var DataModel $this
         */
        if($this->getBackendTransactionStartedByThisInstance()) {
            $backend->transactionCommit();

            $this->_backend_transaction_started = false;
        }
    }

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function rollbackBackendTransaction( DataModel_Backend_Abstract $backend ) {
        $backend->transactionRollback();
	    $this->_backend_transaction_started = false;
    }

}