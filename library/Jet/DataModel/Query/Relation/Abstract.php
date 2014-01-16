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
 * @subpackage DataModel_Query
 */
namespace Jet;

abstract class DataModel_Query_Relation_Abstract extends Object {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $related_data_model_class_name;

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $related_data_model_definition;

	/**
	 * @var DataModel
	 */
	protected $related_data_model_instance;


	/**
	 * @var string
	 */
	protected $join_type = DataModel_Query::JOIN_TYPE_LEFT_JOIN;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}



	/**
	 * @param string $join_type
	 */
	public function setJoinType($join_type) {
		$this->join_type = $join_type;
	}

	/**
	 * @return string
	 */
	public function getJoinType() {
		return $this->join_type;
	}


	/**
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getRelatedDataModelDefinition() {
		if(!$this->related_data_model_definition) {
			$this->related_data_model_definition = $this->getRelatedDataModelInstance()->getDataModelDefinition();
		}
		return $this->related_data_model_definition;
	}

	/**
	 * @return DataModel
	 */
	public function getRelatedDataModelInstance() {
		if(!$this->related_data_model_instance) {
			$this->related_data_model_instance = Factory::getInstance( $this->related_data_model_class_name );
		}

		return $this->related_data_model_instance;
	}

	/**
	 * @param string $related_to_class_name
	 */
	protected function setRelatedToClass( $related_to_class_name ) {
		$this->related_data_model_class_name = $related_to_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]|DataModel_Query_Relation_Outer_JoinByProperty[]
	 */
	abstract public function getJoinByProperties();


}