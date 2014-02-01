<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Relation_External extends DataModel_Definition_Relation_Abstract {



	/**
	 * @param array $definition_data (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct( $definition_data=null ) {

		if($definition_data) {
			$this->setUp( $definition_data );
		}
	}

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data) {

		if(!isset($definition_data['related_to_class_name'])) {
			throw new DataModel_Exception(
				'Outer relation definition: related_to_class_name is not defined  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if(!isset($definition_data['join_by_properties'])) {
			throw new DataModel_Exception(
				'Outer relation definition: join_by_properties is not defined  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		$this->setRelatedToClass($definition_data['related_to_class_name']);

		$related_properties = $this->getRelatedDataModelDefinition()->getProperties();

		foreach($definition_data['join_by_properties'] as $this_model_property=>$related_property_name) {
			if(!isset($related_properties[$related_property_name])) {
				throw new DataModel_Exception(
					'Unknown property '.$definition_data['related_to_class_name'].'::'.$related_property_name.' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			;

			$this->join_by[] = new DataModel_Definition_Relation_JoinBy_Item(
				$related_properties[$related_property_name],
				$this_model_property
			);
		}

	}

}