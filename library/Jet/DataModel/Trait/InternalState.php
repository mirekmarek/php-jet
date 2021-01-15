<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
trait DataModel_Trait_InternalState
{

	/**
	 * @var bool
	 */
	private bool $_data_model_saved = false;

	/**
	 *
	 */
	public function initNewObject(): void
	{
		$this->setIsNew();

		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $data_model_definition
		 */
		$data_model_definition = static::getDataModelDefinition();

		foreach( $data_model_definition->getProperties() as $property_name => $property_definition ) {

			$property_definition->initPropertyDefaultValue( $this->{$property_name} );

		}

	}

	/**
	 *
	 */
	public function setIsNew(): void
	{
		$this->_data_model_saved = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsNew(): bool
	{
		return !$this->_data_model_saved;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved(): bool
	{
		return $this->_data_model_saved;
	}

	/**
	 *
	 */
	public function setIsSaved(): void
	{
		$this->_data_model_saved = true;
	}

}