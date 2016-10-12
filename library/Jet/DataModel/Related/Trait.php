<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

trait DataModel_Related_Trait {

    use DataModel_Trait;

    use DataModel_Related_Trait_Definition {
        DataModel_Related_Trait_Definition::_getDataModelDefinitionInstance insteadof DataModel_Trait;
    }

    use DataModel_Related_Trait_Load;

	/**
	 * @param DataModel_ID_Abstract $parent_ID
	 */
	public function actualizeParentID( DataModel_ID_Abstract $parent_ID ) {
		/**
		 * @var DataModel_Definition_Model_Related_Abstract $definition
		 */
		$definition = $this->getDataModelDefinition();

		foreach( $definition->getParentModelRelationIDProperties() as $property_definition ) {
			$property_name = $property_definition->getName();

			//if(isset($parent_ID[$property_definition->getRelatedToPropertyName()])) {
			if(
				$this->getIsSaved() &&
				$this->{$property_name} != $parent_ID[$property_definition->getRelatedToPropertyName()]
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $parent_ID[$property_definition->getRelatedToPropertyName()];
			//}
		}

	}

	/**
	 * @param DataModel_ID_Abstract $main_ID
	 */
	public function actualizeMainID( DataModel_ID_Abstract $main_ID ) {

		/**
		 * @var DataModel_Definition_Model_Related_Abstract $definition
		 */
		$definition = $this->getDataModelDefinition();

		foreach( $definition->getMainModelRelationIDProperties() as $property_definition ) {

			$property_name = $property_definition->getName();

			//if(isset($main_ID[$property_definition->getRelatedToPropertyName()])) {
			if(
				$this->getIsSaved() &&
				$this->{$property_name} != $main_ID[$property_definition->getRelatedToPropertyName()]
			) {
				$this->setIsNew();
			}

			$this->{$property_name} = $main_ID[$property_definition->getRelatedToPropertyName()];
			//}

		}

		foreach( $definition->getProperties() as  $property_definition) {
			$property_name = $property_definition->getName();

			if( $this->{$property_name} instanceof  DataModel_Related_Interface ) {
				$this->{$property_name}->actualizeMainID( $main_ID );
			}
		}

	}


}
