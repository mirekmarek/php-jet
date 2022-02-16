<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public static function getBackendInstance(): DataModel_Backend
	{
		return DataModel_Backend::get( static::getDataModelDefinition() );
	}

	/**
	 *
	 */
	public function startBackendTransaction(): void
	{
		if( !$this->getBackendTransactionStarted() ) {
			static::getBackendInstance()->setTransactionStarter( $this );
			static::getBackendInstance()->transactionStart();
		}
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStarted(): bool
	{
		return static::getBackendInstance()->getTransactionStarted();
	}

	/**
	 *
	 */
	public function commitBackendTransaction(): void
	{
		if( $this->getBackendTransactionStartedByThisInstance() ) {
			static::getBackendInstance()->transactionCommit();
			static::getBackendInstance()->setTransactionStarter( null );
		}
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStartedByThisInstance(): bool
	{
		/**
		 * @var DataModel $this
		 */
		$starter = static::getBackendInstance()->getTransactionStarter();
		if( !$starter ) {
			return false;
		}

		if( get_class( $starter ) != get_class( $this ) ) {
			return false;
		}

		$id_controller = $this->getIDController();

		if( $starter->getIDController()->toString() != $id_controller->toString() ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function rollbackBackendTransaction(): void
	{
		static::getBackendInstance()->transactionRollback();
	}

}