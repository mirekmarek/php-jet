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
trait DataModel_Related_Trait
{

	use DataModel_Trait;

	/**
	 * @param DataModel_IDController $parent_id
	 */
	public function actualizeParentId( DataModel_IDController $parent_id ) : void
	{
		/**
		 * @var DataModel_Definition_Model_Related $definition
		 */
		$definition = static::getDataModelDefinition();

		foreach( $definition->getParentModelRelationIdProperties() as $property_definition ) {
			$property_name = $property_definition->getName();


			if(
				$this->getIsSaved() &&
				$this->{$property_name}!=$parent_id->getValue( $property_definition->getRelatedToPropertyName() )
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $parent_id->getValue( $property_definition->getRelatedToPropertyName() );

		}

	}

	/**
	 * @param DataModel_IDController $main_id
	 */
	public function actualizeMainId( DataModel_IDController $main_id ) : void
	{

		/**
		 * @var DataModel_Definition_Model_Related $definition
		 */
		$definition = static::getDataModelDefinition();

		foreach( $definition->getMainModelRelationIdProperties() as $property_definition ) {

			$property_name = $property_definition->getName();

			if(
				$this->getIsSaved() &&
				$this->{$property_name}!=$main_id->getValue( $property_definition->getRelatedToPropertyName() )
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $main_id->getValue( $property_definition->getRelatedToPropertyName() );

		}

		foreach( $definition->getProperties() as $property_definition ) {
			$property_name = $property_definition->getName();

			if( $this->{$property_name} instanceof DataModel_Related_Interface ) {
				$this->{$property_name}->actualizeMainId( $main_id );
			}
		}

	}


}
