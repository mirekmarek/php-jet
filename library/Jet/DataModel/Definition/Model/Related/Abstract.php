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
	 * @var string
	 */
	protected $main_model_class_name = '';

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $main_model_relation_join_items = array();

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $main_model_relation_ID_properties = array();

	/**
	 *
	 * @var bool
	 */
	protected $is_sub_related_model = false;

	/**
	 * @var string
	 */
	protected $parent_model_class_name = '';

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $parent_model_relation_join_items = array();

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $parent_model_relation_ID_properties = array();

	/**
	 * @var array
	 */
	protected $__main_ID_glue_defined = array();

	/**
	 * @var array
	 */
	protected $__parent_ID_glue_defined = array();


	/**
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct( $data_model_class_name='' ) {
		if($data_model_class_name) {
			$this->_mainInit($data_model_class_name);

			$this->_initParents();
			$this->_initBackendsConfig();
			$this->_initProperties();
			$this->_initKeys();

			if(!$this->ID_properties) {
				throw new DataModel_Exception(
					'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}
	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents() {

		$parent_model_class_name = Object_Reflection::get( $this->class_name, 'data_model_parent_model_class_name' );

		if(!$parent_model_class_name) {
			throw new DataModel_Exception(
				$this->class_name.' @JetDataModel:parent_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->parent_model_class_name = $parent_model_class_name;

		$main_model_class_name = $parent_model_class_name;

		// Traversing and seeking for main model
		while( ($_parent = Object_Reflection::get( $main_model_class_name, 'data_model_parent_model_class_name' )) ) {

			$main_model_class_name = $_parent;

			$this->is_sub_related_model = true;
		}

		if( !is_subclass_of( $main_model_class_name, 'Jet\\DataModel' ) ) {
			throw new DataModel_Exception(
				'Main parent class '.$main_model_class_name.' is not subclass of Jet\\DataModel ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->main_model_class_name = $main_model_class_name;

	}

	/**
	 *
	 */
	protected function _initBackendsConfig() {
		$main_class_name = $this->main_model_class_name;

		$this->forced_backend_type = Object_Reflection::get( $main_class_name, 'data_model_forced_backend_type', null );
		$this->forced_backend_config = Object_Reflection::get( $main_class_name, 'data_model_forced_backend_config', null );

		$this->forced_cache_enabled = Object_Reflection::get( $main_class_name, 'data_model_forced_cache_enabled', null );
		$this->forced_cache_backend_type = Object_Reflection::get( $main_class_name, 'data_model_forced_cache_backend_type', null );
		$this->forced_cache_backend_config = Object_Reflection::get( $main_class_name, 'data_model_forced_cache_backend_config', null );

		$this->forced_history_enabled = Object_Reflection::get( $this->class_name, 'data_model_forced_history_enabled', null );
		$this->forced_history_backend_type = Object_Reflection::get( $this->class_name, 'data_model_forced_history_backend_type', null );
		$this->forced_history_backend_config = Object_Reflection::get( $this->class_name, 'data_model_forced_history_backend_config', null );
	}

	/**
	 *
	 */
	protected function _initProperties() {

		parent::_initProperties();


		$related_definition_data = $this->_getPropertiesDefinitionData( $this->main_model_class_name );
		foreach( $related_definition_data as $property_name=>$pd ) {
			if(empty($pd['is_ID'])) {
				continue;
			}
			if(
				!in_array($property_name, $this->__main_ID_glue_defined)
			) {
				throw new DataModel_Exception(
					'Class: \''.$this->class_name.'\'  Main model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'main.'.$property_name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}


		if($this->is_sub_related_model) {
			$related_definition_data = $this->_getPropertiesDefinitionData( $this->parent_model_class_name );
			foreach( $related_definition_data as $property_name=>$pd ) {
				if(empty($pd['is_ID'])) {
					continue;
				}
				if(
					!in_array($property_name, $this->__main_ID_glue_defined)
				) {
					throw new DataModel_Exception(
						'Class: \''.$this->class_name.'\'  parent model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'parent.'.$property_name.'\' ',
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

		list( $what, $related_to_property_name ) = $related_to;

		if(
			(
				$what!='parent' &&
				$what!='main'
			) ||
			!$related_to_property_name
		) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.ID\', @JetDataModel:related_to=\'main.ID\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		if(!$this->is_sub_related_model && $what=='parent') {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to = \'parent.'.$related_to_property_name.'\' definition. Use: @JetDataModel:related_to = \'main.'.$related_to_property_name.'\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$related_to_class_name = '';

		if($what=='parent') {
			$related_to_class_name = $this->parent_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->parent_model_relation_ID_properties;
			$target_join_array = &$this->parent_model_relation_join_items;
			$target_glue_defined = &$this->__parent_ID_glue_defined;
		}

		if($what=='main') {
			$related_to_class_name = $this->main_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->main_model_relation_ID_properties;
			$target_join_array = &$this->main_model_relation_join_items;
			$target_glue_defined = &$this->__main_ID_glue_defined;
		}

		if(!isset($related_definition_data[$related_to_property_name])) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_ID_property_data = $related_definition_data[$related_to_property_name];


		$this_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this->class_name,
			$this_ID_property_name,
			$parent_ID_property_data
		);

		$this_ID_property->setUpRelation($related_to_class_name, $related_to_property_name);

		$this->properties[$this_ID_property_name] = $this_ID_property;
		$target_properties_array[$this_ID_property_name] = $this_ID_property;

		$target_join_array[] = new DataModel_Definition_Relation_JoinBy_Item( $this, $this_ID_property, $related_to_class_name, $related_to_property_name );

		$target_glue_defined[] = $related_to_property_name;

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

		foreach($this->getProperties() as $property ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if(!$property->getIsDataModel()) {
				continue;
			}

			$relations = array_merge(
				$relations,
				$property->getDataModelDefinition()->getInternalRelations( $parent_model_class_name )
			);
		}

		return $relations;
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getMainModelRelationIDProperties() {
		return $this->main_model_relation_ID_properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getParentModelRelationIDProperties() {
		return $this->parent_model_relation_ID_properties;
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
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->main_model_class_name );
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentRelatedModelDefinition() {
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->parent_model_class_name );
	}
}