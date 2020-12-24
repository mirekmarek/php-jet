<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Trait_Delete
{
	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() : void
	{
		/**
		 * @var DataModel $this
		 */
		if( $this->getLoadFilter() ) {
			throw new DataModel_Exception(
				'Nothing to delete... Object is not completely loaded. (Class: \''.get_class(
					$this
				).'\', Id:\''.$this->getIDController().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE
			);
		}

		if(
			!$this->getIDController() ||
			!$this->getIsSaved()
		) {
			throw new DataModel_Exception(
				'Nothing to delete... Object was not loaded. (Class: \''.get_class(
					$this
				).'\', Id:\''.$this->getIDController().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE
			);
		}

		/**
		 * @var DataModel_Backend          $backend
		 * @var DataModel_Definition_Model $definition
		 */
		$backend = static::getBackendInstance();
		$definition = static::getDataModelDefinition();

		$this->startBackendTransaction();

		try {

			foreach( $definition->getProperties() as $property_name => $property_definition ) {

				$prop = $this->{$property_name};
				if(
					($prop instanceof DataModel_Related_Interface) ||
					($prop instanceof DataModel_Related_Iterator_Interface)
				) {
					$prop->delete();
				}
			}

			/**
			 * @var DataModel_IDController $id_controller
			 */
			$id_controller = $this->getIDController();

			$backend->delete( $id_controller->getQuery() );
		} catch( \Exception $e ) {
			$this->rollbackBackendTransaction();

			throw $e;
		}

		$this->commitBackendTransaction();

		$this->afterDelete();
	}
}