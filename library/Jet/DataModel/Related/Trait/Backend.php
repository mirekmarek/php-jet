<?php
/**
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
 * @subpackage DataModel_Related
 */
namespace Jet;

trait DataModel_Related_Trait_Backend {

    /**
     * @return bool
     */
    public function getBackendTransactionStarted() {
        /**
         * @var DataModel_Interface $main_model_instance
         */
        $main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');

        if(
            $main_model_instance &&
            $main_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

        /**
         * @var DataModel_Related_Interface|DataModel_Interface $parent_model_instance
         */
        $parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
        if(
            $parent_model_instance &&
            $parent_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

        $backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );

        return $backend_transaction_started;
    }

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance() {
        if(!$this->getBackendTransactionStarted()) {
            return false;
        }

        /**
         * @var DataModel $main_model_instance
         */
        $main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(
            $main_model_instance &&
            $main_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }

        /**
         * @var DataModel_Related_Interface|DataModel_Interface $parent_model_instance
         */
        $parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
        if(
            $parent_model_instance &&
            $parent_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }

        return true;
    }

}