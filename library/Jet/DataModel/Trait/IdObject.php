<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//TODO: IdObject prejmenovat na IDController
/**
 *
 */
trait DataModel_Trait_IdObject
{

	/**
	 * @var DataModel_Id
	 */
	private $_id_object;

	/**
	 * Returns ID
	 *
	 * @return DataModel_Id
	 */
	public function getIdObject()
	{
		/**
		 * @var DataModel $this
		 */

		if( !$this->_id_object ) {
			$this->_id_object = static::getEmptyIdObject();

			/** @noinspection PhpParamsInspection */
			$this->_id_object->joinDataModel( $this );

			foreach( $this->_id_object->getPropertyNames() as $property_name ) {
				$this->_id_object->joinObjectProperty( $property_name, $this->{$property_name} );
			}

		}


		return $this->_id_object;
	}


	/**
	 * @return DataModel_Id
	 */
	public static function getEmptyIdObject()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return static::getDataModelDefinition()->getEmptyIdInstance();
	}

}