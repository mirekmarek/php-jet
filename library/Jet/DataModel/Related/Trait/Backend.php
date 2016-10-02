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

	    /** @noinspection PhpUndefinedMethodInspection */
        if(
            $this->_main_model_instance &&
            $this->_main_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

	    /** @noinspection PhpUndefinedMethodInspection */
        if(
            $this->_parent_model_instance &&
            $this->_parent_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

        return $this->_backend_transaction_started;
    }

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance() {
        if(!$this->getBackendTransactionStarted()) {
            return false;
        }

	    /** @noinspection PhpUndefinedMethodInspection */
        if(
            $this->_main_model_instance &&
            $this->_main_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }


	    /** @noinspection PhpUndefinedMethodInspection */
        if(
            $this->_parent_model_instance &&
            $this->_parent_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }

        return true;
    }

}