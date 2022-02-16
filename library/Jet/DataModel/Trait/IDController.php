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
trait DataModel_Trait_IDController
{

	/**
	 * @var ?DataModel_IDController
	 */
	private ?DataModel_IDController $_id_controller = null;

	/**
	 * Returns ID
	 *
	 * @return DataModel_IDController
	 */
	public function getIDController(): DataModel_IDController
	{
		if( !$this->_id_controller ) {
			$this->_id_controller = static::getEmptyIDController();

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
	public static function getEmptyIDController(): DataModel_IDController
	{
		return static::getDataModelDefinition()->getIDController();
	}

}