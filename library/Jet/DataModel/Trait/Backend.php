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
     * Returns backend instance
     *
     * @return DataModel_Backend_Abstract
     */
    public static function getBackendInstance() {
        return static::getDataModelDefinition()->getBackendInstance();
    }


    /**
     * @return bool
     */
    public function getBackendTransactionStarted() {
        /**
         * @var DataModel $this
         */
        $backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );

        return $backend_transaction_started;
    }

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance() {
        /**
         * @var DataModel $this
         */
        $backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );

        return $backend_transaction_started;
    }

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function startBackendTransaction( DataModel_Backend_Abstract $backend ) {
        /**
         * @var DataModel $this
         */
        if(!$this->getBackendTransactionStarted()) {
            $backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );
            $backend_transaction_started = true;

            $backend->transactionStart();;
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

            $backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );
            $backend_transaction_started = false;
        }
    }

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function rollbackBackendTransaction( DataModel_Backend_Abstract $backend ) {
        $backend->transactionRollback();
    }

}