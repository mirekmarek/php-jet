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
	 * @var DataModel_Definition_Model_Abstract[]
	 */
	protected $related_models = array();

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
	 * @param string $data_model_class_name
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function  __construct( $data_model_class_name ) {
		$this->_mainInit($data_model_class_name);

		/**
		 * @var DataModel_Related_MtoN $data_model_class_name
		 */
		$M_model_class_name = $data_model_class_name::getDataModelDefinitionMModelClassName();
		$N_model_class_name = $data_model_class_name::getDataModelDefinitionNModelClassName();

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

		$M_related_model_definition = DataModel::getDataModelDefinition( $M_model_class_name );
		$N_related_model_definition = DataModel::getDataModelDefinition( $N_model_class_name );


		$M_model_name = $M_related_model_definition->getModelName();
		$N_model_name = $N_related_model_definition->getModelName();

		$this->related_models = array();
		$this->related_models[$M_model_name] = $M_related_model_definition;
		$this->related_models[$N_model_name] = $N_related_model_definition;

		$this->relation_ID_properties[$M_model_name] = array();
		$this->relation_ID_properties[$N_model_name] = array();

		$this->join_by[$M_model_name] = array();
		$this->join_by[$N_model_name] = array();

		$this->_glue_defined[$M_model_name] = array();
		$this->_glue_defined[$N_model_name] = array();

		$this->_initProperties();

	}

	/**
	 *
	 */
	protected function _initProperties() {

		parent::_initProperties();

		foreach( $this->_glue_defined as $model_name=>$glue_defined ) {

			/**
			 * @var DataModel_Definition_Model_Abstract $related_model_definition
			 */
			$related_model_definition = $this->related_models[$model_name];

			$ID_properties = $related_model_definition->getIDProperties();

			foreach( $ID_properties as $main_ID_property_name => $main_ID_property ) {
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
	 *
	 * @throws DataModel_Exception
	 */
	protected function _initGlueProperty( $this_ID_property_name, $related_to ) {
		$related_to = explode('.', $related_to);
		if(count($related_to)!=2) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'related_model_name_m.ID\', @JetDataModel:related_to=\'related_model_name_n.ID\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $related_model_name, $related_ID_property_name ) = $related_to;

		if(!isset($this->related_models[$related_model_name]) ) {
			throw new DataModel_Exception(
				'Unknown related data model \''.$related_model_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$properties = $this->related_models[$related_model_name]->getProperties();

		if(!isset($properties[$related_ID_property_name])) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_model_name.'.'.$related_ID_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}


		$related_ID_property = $properties[$related_ID_property_name];


		$this_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this,
			$this_ID_property_name,
			array(
				'type' => $related_ID_property->getType(),
				'is_ID' => true
			)
		);


		DataModel_Definition_Property_Abstract::cloneProperty( $related_ID_property, $this_ID_property );
		$this_ID_property->setUpRelation($related_ID_property);


		$this->properties[$this_ID_property_name] = $this_ID_property;

		$this->relation_ID_properties[$related_model_name][$this_ID_property_name] = $this_ID_property;
		$this->join_by[$related_model_name][] = new DataModel_Definition_Relation_JoinBy_Item( $related_ID_property, $this_ID_property, $this );
		$this->_glue_defined[$related_model_name][] = $related_ID_property->getName();

	}


	/**
	 * @param string $parent_model_class_name
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Internal[]
	 */
	public function getInternalRelations( $parent_model_class_name ) {

		$M_model_name = null;
		$N_model_name = null;

		foreach( $this->related_models as $model_name=>$model_definition ) {
			if( $model_definition->getClassName()==$parent_model_class_name ) {
				$M_model_name = $model_name;
			}
			$N_model_name = $model_name;
		}

		if(!$M_model_name) {
			throw new DataModel_Exception(
				'Class \''.$parent_model_class_name.'\' is not related to me',
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


		$N_model_definition = $this->related_models[$N_model_name];

		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $glue_n_relation_join_by
		 */
		$glue_n_relation_join_by = $this->join_by[$N_model_name];
		$relations[ $N_model_definition->getModelName() ] = new DataModel_Definition_Relation_Internal( $N_model_definition, $glue_n_relation_join_by );
		$relations[ $N_model_definition->getModelName() ]->setRequiredRelations( array( $this->getModelName() ) );


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
		foreach( array_keys($this->related_models) as $model_name ) {
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
		foreach( array_keys($this->related_models) as $model_name ) {
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
		if(!isset($this->related_models[$model_name])) {
			return null;
		}

		return $this->related_models[$model_name];
	}

}