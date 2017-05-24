<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Trait_Backend
{

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance()
	{
		return DataModel_Backend::get( static::getDataModelDefinition() );
	}

	/**
	 *
	 */
	public function startBackendTransaction()
	{
		/**
		 * @var DataModel $this
		 */
		if( !$this->getBackendTransactionStarted() ) {
			static::getBackendInstance()->setTransactionStarter( $this );
			static::getBackendInstance()->transactionStart();
		}
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStarted()
	{
		return static::getBackendInstance()->getTransactionStarted();
	}

	/**
	 *
	 */
	public function commitBackendTransaction()
	{
		/**
		 * @var DataModel $this
		 */
		if( $this->getBackendTransactionStartedByThisInstance() ) {
			static::getBackendInstance()->transactionCommit();
			static::getBackendInstance()->setTransactionStarter( null );
		}
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStartedByThisInstance()
	{
		/**
		 * @var DataModel $this
		 */
		$starter = static::getBackendInstance()->getTransactionStarter();
		if( !$starter ) {
			return false;
		}

		if( get_class( $starter )!=get_class( $this ) ) {
			return false;
		}

		if( $starter->getIdObject()->toString()!=$this->getIdObject()->toString() ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function rollbackBackendTransaction()
	{
		static::getBackendInstance()->transactionRollback();
	}

}