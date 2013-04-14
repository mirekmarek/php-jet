<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Query_Relation_Outer extends DataModel_Query_Relation_Abstract {

	/**
	 * @var DataModel_Query_Relation_Outer_JoinByProperty[]
	 */
	protected $join_by_properties = array();


	/**
	 * @param string $name
	 * @param array $definition_data (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct( $name, $definition_data=null ) {
		$this->name = $name;

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
		//TODO: check


		$this->setRelatedToClass($definition_data["related_to_class_name"]);

		$related_properties = $this->getRelatedDataModelDefinition()->getProperties();

		foreach($definition_data["join_by_properties"] as $related_property_name=>$this_model_property) {
			if(!isset($related_properties[$related_property_name])) {
				//TODO: scream!
				throw new DataModel_Exception(
					" {$definition_data["related_to_class_name"]}::{$related_property_name} ",
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			;

			$this->join_by_properties[] = new DataModel_Query_Relation_Outer_JoinByProperty(
				$this,
				$related_properties[$related_property_name],
				$this_model_property
			);
		}

	}

	/**
	 *
	 * @return DataModel_Query_Relation_Outer_JoinByProperty[]
	 */
	public function getJoinByProperties() {
		return $this->join_by_properties;
	}





}