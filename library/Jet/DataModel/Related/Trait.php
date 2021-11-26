<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait DataModel_Related_Trait
{

	use DataModel_Trait;

	public function __construct()
	{

	}

	/**
	 * @param DataModel_IDController|null $main_id
	 * @param DataModel_IDController|null $parent_id
	 */
	public function actualizeRelations( ?DataModel_IDController $main_id=null, ?DataModel_IDController $parent_id=null ): void
	{
		/**
		 * @var DataModel_Definition_Model_Related $definition
		 */
		$definition = static::getDataModelDefinition();

		if($main_id) {
			foreach( $definition->getMainModelRelationIdProperties() as $property_definition ) {

				$property_name = $property_definition->getName();

				if($this->{$property_name} === $main_id->getValue( $property_definition->getRelatedToPropertyName() )) {
					continue;
				}

				if( $this->getIsSaved()) {
					$this->setIsNew();
				}

				$this->{$property_name} = $main_id->getValue( $property_definition->getRelatedToPropertyName() );

			}
		}


		if($parent_id) {
			foreach( $definition->getParentModelRelationIdProperties() as $property_definition ) {
				$property_name = $property_definition->getName();

				if($this->{$property_name} === $parent_id->getValue( $property_definition->getRelatedToPropertyName() )) {
					continue;
				}

				if( $this->getIsSaved() ) {
					$this->setIsNew();
				}

				$this->{$property_name} = $parent_id->getValue( $property_definition->getRelatedToPropertyName() );

			}
		}

		foreach( $definition->getProperties() as $property_name => $property_definition ) {
			if(!($property_definition instanceof DataModel_Definition_Property_DataModel)) {
				continue;
			}

			$prop = $this->{$property_name};

			if(
				is_object($prop) &&
				$prop instanceof DataModel_Related_Interface
			) {
				$prop->actualizeRelations( $main_id, $this->getIDController() );
			}

			if(is_array($prop)) {
				foreach($prop as $v) {
					if( $v instanceof DataModel_Related_Interface ) {
						$v->actualizeRelations( $main_id, $this->getIDController() );
					}
				}
			}
		}

	}



}
