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

class DataModel_Definition_Model_Related_Abstract extends DataModel_Definition_Model_Abstract {

	/**
	 *
	 * @var bool
	 */
	protected $_is_sub_related_model = false;

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $main_model_relation_join_items = array();

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $parent_model_relation_join_items = array();

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $_main_model_relation_ID_properties = array();

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $_parent_model_relation_ID_properties = array();

	/**
	 *
	 * @var DataModel_Definition_Model_Main
	 */
	protected $_main_model_definition = null;

	/**
	 *
	 * @var DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	protected $_parent_related_model_definition = null;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $__main_ID_properties;

	/**
	 * @var array
	 */
	protected $__main_ID_glue_defined = array();

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $__parent_ID_properties;

	/**
	 * @var array
	 */
	protected $__parent_ID_glue_defined = array();


	/**
	 * @param string $data_model_class_name
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct( $data_model_class_name ) {
		$this->_mainInit($data_model_class_name);

		/**
		 * @var DataModel_Related_Abstract $data_model_class_name
		 */
		$parent_model_class_name = $data_model_class_name::getParentModelClassName();

		if(!$parent_model_class_name) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:parent_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->_parent_related_model_definition = DataModel::getDataModelDefinition( $parent_model_class_name );


		$_parent_definition = $this->_parent_related_model_definition;

		while( !($_parent_definition instanceof DataModel_Definition_Model_Main) ) {
			/**
			 * Temporary ... Traversing and seeking for main model
			 *
			 * @var DataModel_Definition_Model_Related_1to1 $_parent_definition
			 */
			$_parent_definition = $_parent_definition->getParentRelatedModelDefinition();

			$this->_is_sub_related_model = true;
		}

		$this->_main_model_definition = $_parent_definition;

		$this->__main_ID_properties = $this->_main_model_definition->getIDProperties();
		if($this->_is_sub_related_model) {
			$this->__parent_ID_properties = $this->_parent_related_model_definition->getIDProperties();
		}

		$this->_initProperties();

		if(!$this->ID_properties) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}
	}

	/**
	 *
	 */
	protected function _initProperties() {

		parent::_initProperties();

		foreach( $this->__main_ID_properties as $main_ID_property_name => $main_ID_property ) {
			if(!in_array($main_ID_property_name, $this->__main_ID_glue_defined)) {
				throw new DataModel_Exception(
					'Class: \''.$this->class_name.'\'  Main model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'main.'.$main_ID_property_name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

		if($this->_is_sub_related_model) {
			foreach( $this->__parent_ID_properties as $parent_ID_property_name => $parent_ID_property ) {
				if(!in_array($parent_ID_property_name, $this->__parent_ID_glue_defined)) {
					throw new DataModel_Exception(
						'Class: \''.$this->class_name.'\'  parent model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'parent.'.$parent_ID_property_name.'\' ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}

	}

	/**
	 * @param string $this_ID_property_name
	 * @param string $related_to
	 *
	 * @throws DataModel_Exception
	 */
	protected function _initGlueProperty( $this_ID_property_name, $related_to ) {

		$related_to = explode('.', $related_to);
		if(count($related_to)!=2) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.ID\', @JetDataModel:related_to=\'main.ID\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $what, $parent_ID_property_name ) = $related_to;

		if(
			(
				$what!='parent' &&
				$what!='main'
			) ||
			!$parent_ID_property_name
		) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.ID\', @JetDataModel:related_to=\'main.ID\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		if(!$this->_is_sub_related_model && $what=='parent') {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to = \'parent.'.$parent_ID_property_name.'\' definition. Use: @JetDataModel:related_to = \'main.'.$parent_ID_property_name.'\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if($what=='parent') {
			$properties = $this->__parent_ID_properties;
			$target_properties_array = &$this->_parent_model_relation_ID_properties;
			$target_join_array = &$this->parent_model_relation_join_items;
			$target_glue_defined = &$this->__parent_ID_glue_defined;
		}


		if($what=='main') {
			$properties = $this->__main_ID_properties;
			$target_properties_array = &$this->_main_model_relation_ID_properties;
			$target_join_array = &$this->main_model_relation_join_items;
			$target_glue_defined = &$this->__main_ID_glue_defined;
		}

		if(!isset($properties[$parent_ID_property_name])) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$what.'.'.$parent_ID_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_ID_property = $properties[$parent_ID_property_name];


		$this_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this,
			$this_ID_property_name,
			array(
				'type' => $parent_ID_property->getType(),
				'is_ID' => true
			)
		);


		DataModel_Definition_Property_Abstract::cloneProperty( $parent_ID_property, $this_ID_property );
		$this_ID_property->setUpRelation($parent_ID_property);


		$this->properties[$this_ID_property_name] = $this_ID_property;
		$target_properties_array[$this_ID_property_name] = $this_ID_property;

		$target_join_array[] = new DataModel_Definition_Relation_JoinBy_Item( $parent_ID_property, $this_ID_property, $this );

		$target_glue_defined[] = $parent_ID_property->getName();

	}

	/**
	 * @param string $parent_model_class_name
	 *
	 * @return DataModel_Definition_Relation_Internal[]
	 */
	public function getInternalRelations(
		/** @noinspection PhpUnusedParameterInspection */
		$parent_model_class_name
	) {
		$relations = array();

		$relations[$this->getModelName()] = new DataModel_Definition_Relation_Internal(
			$this,
			$this->getMainModelRelationJoinItems()
		);

		if($this->_is_sub_related_model) {
			$relations[$this->getModelName()] = new DataModel_Definition_Relation_Internal(
				$this,
				$this->getParentModelRelationJoinItems()
			);
		}

		return $relations;
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getMainModelRelationIDProperties() {
		return $this->_main_model_relation_ID_properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getParentModelRelationIDProperties() {
		return $this->_parent_model_relation_ID_properties;
	}

	/**
	 * @return DataModel_Definition_Relation_JoinBy_Item[]
	 */
	public function getMainModelRelationJoinItems() {
		return $this->main_model_relation_join_items;
	}

	/**
	 * @return DataModel_Definition_Relation_JoinBy_Item[]
	 */
	public function getParentModelRelationJoinItems() {
		return $this->parent_model_relation_join_items;
	}



	/**
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModelDefinition() {
		return $this->_main_model_definition;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentRelatedModelDefinition() {
		return $this->_parent_related_model_definition;
	}
}