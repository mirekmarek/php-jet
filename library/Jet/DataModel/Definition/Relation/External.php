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
class DataModel_Definition_Relation_External extends DataModel_Definition_Relation
{


	/**
	 * @param string $this_model_class_name
	 * @param ?array $definition_data
	 *
	 */
	public function __construct( string $this_model_class_name = '', ?array $definition_data = null )
	{

		if( $definition_data ) {
			$this->setUp( $this_model_class_name, $definition_data );
		}
	}

	/**
	 * @param string $this_model_class_name
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( string $this_model_class_name, array $definition_data ): void
	{

		if( !isset( $definition_data['related_to_class_name'] ) ) {
			throw new DataModel_Exception(
				'Outer relation definition: related_to_class_name is not defined  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !isset( $definition_data['join_by_properties'] ) ) {
			throw new DataModel_Exception(
				'Outer relation definition: join_by_properties is not defined  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !isset( $definition_data['join_type'] ) ) {
			throw new DataModel_Exception(
				'Outer relation definition: join_type is not defined  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !array_key_exists( 'required_relations', $definition_data ) ) {
			$definition_data['required_relations'] = [];
		}


		$this->this_data_model_class_name = $this_model_class_name;
		$this->related_data_model_class_name = $definition_data['related_to_class_name'];
		$this->join_type = $definition_data['join_type'];
		$this->required_relations = $definition_data['required_relations'];


		$related_properties = $this->getRelatedDataModelDefinition()->getProperties();

		foreach( $definition_data['join_by_properties'] as $this_property_name => $related_property_name ) {

			if( !isset( $related_properties[$related_property_name] ) ) {
				throw new DataModel_Exception(
					'Unknown property ' . $definition_data['related_to_class_name'] . '::' . $related_property_name . ' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}


			$this->join_by[] = new DataModel_Definition_Relation_Join_Item(
				$this,
				$this_property_name,
				$related_property_name
			);
		}

	}

}