<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
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
			/** @noinspection PhpParamsInspection */
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

		/**
		 * @var DataModel_IDController $id_controller
		 */
		$id_controller = $this->getIDController();

		if( $starter->getIDController()->toString()!=$id_controller->toString() ) {
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