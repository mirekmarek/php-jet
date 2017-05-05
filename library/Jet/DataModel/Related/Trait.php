<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Related_Trait
 * @package Jet
 */
trait DataModel_Related_Trait
{

	use DataModel_Trait;

	use DataModel_Related_Trait_Definition {
		DataModel_Related_Trait_Definition::_getDataModelDefinitionInstance insteadof DataModel_Trait;
	}

	use DataModel_Related_Trait_Load;

	/**
	 * @param DataModel_Id_Abstract $parent_id
	 */
	public function actualizeParentId( DataModel_Id_Abstract $parent_id )
	{
		/**
		 * @var DataModel_Definition_Model_Related_Abstract $definition
		 */
		$definition = static::getDataModelDefinition();

		foreach( $definition->getParentModelRelationIdProperties() as $property_definition ) {
			$property_name = $property_definition->getName();


			if( $this->getIsSaved()&&$this->{$property_name}!=$parent_id[$property_definition->getRelatedToPropertyName(
				)]
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $parent_id[$property_definition->getRelatedToPropertyName()];

		}

	}

	/**
	 * @param DataModel_Id_Abstract $main_id
	 */
	public function actualizeMainId( DataModel_Id_Abstract $main_id )
	{

		/**
		 * @var DataModel_Definition_Model_Related_Abstract $definition
		 */
		$definition = static::getDataModelDefinition();

		foreach( $definition->getMainModelRelationIdProperties() as $property_definition ) {

			$property_name = $property_definition->getName();

			if( $this->getIsSaved()&&$this->{$property_name}!=$main_id[$property_definition->getRelatedToPropertyName(
				)]
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $main_id[$property_definition->getRelatedToPropertyName()];

		}

		foreach( $definition->getProperties() as $property_definition ) {
			$property_name = $property_definition->getName();

			if( $this->{$property_name} instanceof DataModel_Related_Interface ) {
				$this->{$property_name}->actualizeMainId( $main_id );
			}
		}

	}


}
