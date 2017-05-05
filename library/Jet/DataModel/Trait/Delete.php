<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_Delete
 * @package Jet
 */
trait DataModel_Trait_Delete
{
	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete()
	{
		/**
		 * @var DataModel $this
		 */
		if( $this->getLoadFilter() ) {
			throw new DataModel_Exception(
				'Nothing to delete... Object is not completely loaded. (Class: \''.get_class(
					$this
				).'\', Id:\''.$this->getIdObject().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE
			);
		}

		if( !$this->getIdObject()||!$this->getIsSaved() ) {
			throw new DataModel_Exception(
				'Nothing to delete... Object was not loaded. (Class: \''.get_class(
					$this
				).'\', Id:\''.$this->getIdObject().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE
			);
		}

		/**
		 * @var DataModel_Backend_Abstract          $backend
		 * @var DataModel_Definition_Model_Abstract $definition
		 */
		$backend = static::getBackendInstance();
		$definition = static::getDataModelDefinition();

		$this->startBackendTransaction();

		try {

			foreach( $definition->getProperties() as $property_name => $property_definition ) {
				$prop = $this->{$property_name};
				if( $prop instanceof DataModel_Related_Interface ) {
					$prop->delete();
				}
			}

			$backend->delete( $this->getIdObject()->getQuery() );
		} catch( Exception $e ) {
			$this->rollbackBackendTransaction();

			throw $e;
		}

		$this->commitBackendTransaction();

		$this->afterDelete();
	}
}