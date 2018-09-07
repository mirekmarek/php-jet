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
trait DataModel_Trait_IDController
{

	/**
	 * @var DataModel_IDController
	 */
	private $_id_controller;

	/**
	 * Returns ID
	 *
	 * @return DataModel_IDController
	 */
	public function getIDController()
	{
		/**
		 * @var DataModel $this
		 */

		if( !$this->_id_controller ) {
			$this->_id_controller = static::getEmptyIDController();

			/** @noinspection PhpParamsInspection */
			$this->_id_controller->assocDataModelInstance( $this );

			foreach( $this->_id_controller->getPropertyNames() as $property_name ) {
				$this->_id_controller->assocDataModelInstanceProperty( $property_name, $this->{$property_name} );
			}

		}


		return $this->_id_controller;
	}


	/**
	 * @return DataModel_IDController
	 */
	public static function getEmptyIDController()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return static::getDataModelDefinition()->getIDController();
	}

}