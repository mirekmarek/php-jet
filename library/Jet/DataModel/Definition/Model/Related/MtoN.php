<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;


class DataModel_Definition_Model_Related_MtoN extends DataModel_Definition_Model_Abstract {
	/**
	 * @var string
	 */
	protected $M_model_class_name = '';

	/**
	 * @var string[]
	 */
	protected $related_model_class_names = array();


	/**
	 * @var DataModel_Definition_Property_Abstract[][]
	 */
	protected $relation_ID_properties = array();

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[][]
	 */
	protected $join_by = array();

	/**
	 * @var array
	 */
	protected $_glue_defined = array();

	/**
	 *
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function  __construct( $data_model_class_name='' ) {
		if($data_model_class_name) {
			$this->_mainInit($data_model_class_name);

			$this->_initParents();
			$this->_initBackendsConfig();
			$this->_initProperties();
			$this->_initKeys();
		}
	}


    /**
     * @throws DataModel_Exception
     */
    protected function _initIDclass() {
    }


	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents() {
		/**
		 * @var DataModel_Related_MtoN $data_model_class_name
		 */
		$M_model_class_name = Object_Reflection::get( $this->class_name, 'M_model_class_name', null );
		$N_model_class_name = Object_Reflection::get( $this->class_name, 'N_model_class_name', null );

		if(!$M_model_class_name) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:M_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		if(!$N_model_class_name) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:N_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$M_model_name = $this->_getModelNameDefinition( $M_model_class_name );
		$N_model_name = $this->_getModelNameDefinition( $N_model_class_name );


		$this->relation_ID_properties[$M_model_name] = array();
		$this->relation_ID_properties[$N_model_name] = array();

		$this->join_by[$M_model_name] = array();
		$this->join_by[$N_model_name] = array();

		$this->_glue_defined[$M_model_name] = array();
		$this->_glue_defined[$N_model_name] = array();

		$this->M_model_class_name = $M_model_class_name;

		$this->related_model_class_names[$M_model_name] = $M_model_class_name;
		$this->related_model_class_names[$N_model_name] = $N_model_class_name;

	}

	/**
	 *
	 */
	protected function _initBackendsConfig() {
		$main_class_name = $this->M_model_class_name;

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

		foreach( $this->_glue_defined as $model_name=>$glue_defined ) {

			$related_definition_data = $this->_getPropertiesDefinitionData( $this->related_model_class_names[$model_name] );

			foreach( $related_definition_data as $main_ID_property_name => $pd ) {

				if(empty($pd['is_ID'])) {
					continue;
				}

				if(!in_array($main_ID_property_name, $glue_defined) ) {
					throw new DataModel_Exception(
						'Class \''.$this->class_name.'\':  Model \''.$model_name.'\' relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \''.$model_name.'.'.$main_ID_property_name.'\' ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}
	}

	/**
	 * @param string $this_ID_property_name
	 * @param string $related_to
	 * @param array $property_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract
	 *
	 */
	protected function _initGlueProperty( $this_ID_property_name, $related_to, $property_definition_data ) {

		$related_to = explode('.', $related_to);
		if(count($related_to)!=2) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'related_model_name_m.ID\', @JetDataModel:related_to=\'related_model_name_n.ID\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $related_model_name, $related_to_property_name ) = $related_to;

		if(!isset($this->related_model_class_names[$related_model_name]) ) {
			throw new DataModel_Exception(
				'Unknown related data model name \''.$related_model_name.'\' (in class \''.$this->class_name.'\', property: \''.$this_ID_property_name.'\') ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$related_to_class_name = $this->related_model_class_names[$related_model_name];
		$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );


		if(!isset($related_definition_data[$related_to_property_name])) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\' (in class \''.$this->class_name.'\', property: \''.$this_ID_property_name.'\')',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}


		$this_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this->class_name,
			$this_ID_property_name,
			$related_definition_data[$related_to_property_name]
		);


		$this_ID_property->setUpRelation($related_to_class_name, $related_to_property_name);

		$this->properties[$this_ID_property_name] = $this_ID_property;

		$this->relation_ID_properties[$related_model_name][$this_ID_property_name] = $this_ID_property;
		$this->join_by[$related_model_name][] = new DataModel_Definition_Relation_JoinBy_Item( $this, $this_ID_property, $related_to_class_name, $related_to_property_name );
		$this->_glue_defined[$related_model_name][] = $related_to_property_name;

		return $this_ID_property;
	}

	/**
	 * @return string
	 */
	public function getMModelClassName() {
		return $this->M_model_class_name;
	}


	/**
	 * @param string $parent_model_class_name
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Internal[]
	 */
	public function getInternalRelations( $parent_model_class_name ) {

		$M_model_name = DataModel::getDataModelDefinition( $parent_model_class_name )->getModelName();

		$is_related = false;

		$N_model_name = null;
		$N_class_name = null;

		foreach( $this->related_model_class_names as $model_name=>$class_name ) {
			if( $M_model_name==$model_name ) {
				$is_related = true;
			} else {
				$N_model_name = $model_name;
				$N_class_name = $class_name;

			}
		}

		if(!$is_related) {
			throw new DataModel_Exception(
				'Class \''.$parent_model_class_name.'\' is not related to me (Class: \''.$this->class_name.'\')',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		/**
		 * @var DataModel_Definition_Relation_Internal[] $relations
		 */
		$relations = array();


		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $main_glue_relation_join_by
		 */
		$main_glue_relation_join_by = $this->join_by[$M_model_name];
		$relations[ $this->getModelName() ] = new DataModel_Definition_Relation_Internal( $this, $main_glue_relation_join_by );


		$N_model_definition = DataModel_Definition_Model_Abstract::getDataModelDefinition($N_class_name);

		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $glue_n_relation_join_by
		 */
		$glue_n_relation_join_by = $this->join_by[$N_model_name];
		$relations[ $N_model_name ] = new DataModel_Definition_Relation_Internal( $N_model_definition, $glue_n_relation_join_by );
		$relations[ $N_model_name ]->setRequiredRelations( array( $this->getModelName() ) );


		return $relations;
	}

	/**
	 * @param string $model_name
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getRelationIDProperties( $model_name ) {
		return $this->relation_ID_properties[$model_name];
	}

	/**
	 * @param string $M_model_name
	 *
	 * @return string|null
	 */
	public function getNModelName( $M_model_name ) {
		foreach( array_keys($this->related_model_class_names) as $model_name ) {
			if($model_name!=$M_model_name) {
				return $model_name;
			}
		}

		return null;
	}

	/**
	 * @param string $N_model_name
	 *
	 * @return string|null
	 */
	public function getMModelName( $N_model_name ) {
		foreach( array_keys($this->related_model_class_names) as $model_name ) {
			if($model_name!=$N_model_name) {
				return $model_name;
			}
		}

		return null;
	}

	/**
	 * @param string $model_name
	 *
	 * @return DataModel_Definition_Model_Abstract|null
	 */
	public function getRelatedModelDefinition( $model_name ) {
		if(!isset($this->related_model_class_names[$model_name])) {
			return null;
		}

		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->related_model_class_names[$model_name] );
	}

}
