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
	 * @var DataModel_Backend_Abstract
	 */
	protected static $_backend = false;

    /**
     * Returns backend instance
     *
     * @return DataModel_Backend_Abstract
     */
    public static function getBackendInstance() {

	    if(!static::$_backend) {
		    /** @noinspection PhpUndefinedMethodInspection */
		    static::$_backend = static::getDataModelDefinition()->getBackendInstance();

	    }

	    return static::$_backend;
    }


	/**
	 * @return bool
	 */
    public function getBackendTransactionStarted() {
        return $this->getBackendInstance()->getTransactionStarted();
    }

	/**
	 * @return bool
	 */
    public function getBackendTransactionStartedByThisInstance() {
	    /**
	     * @var DataModel $this
	     */
    	$starter = $this->getBackendInstance()->getTransactionStarter();
	    if(!$starter) {
	    	return false;
	    }

	    if(get_class($starter)!=get_class($this)) {
	    	return false;
	    }

	    if($starter->getIdObject()->toString()!=$this->getIdObject()->toString()) {
	    	return false;
	    }

	    return true;
    }

    /**
     *
     */
    public function startBackendTransaction() {
        /**
         * @var DataModel $this
         */
        if(!$this->getBackendTransactionStarted()) {
	        $this->getBackendInstance()->setTransactionStarter($this);
	        $this->getBackendInstance()->transactionStart();
        }
    }

    /**
     *
     */
    public function commitBackendTransaction() {
        /**
         * @var DataModel $this
         */
        if($this->getBackendTransactionStartedByThisInstance()) {
	        $this->getBackendInstance()->transactionCommit();
	        $this->getBackendInstance()->setTransactionStarter(null);
        }
    }

    /**
     *
     */
    public function rollbackBackendTransaction() {
	    $this->getBackendInstance()->transactionRollback();
    }

}