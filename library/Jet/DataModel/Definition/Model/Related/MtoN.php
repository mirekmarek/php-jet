<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 * Class DataModel_Definition_Model_Related_MtoN
 * @package Jet
 */
class DataModel_Definition_Model_Related_MtoN extends DataModel_Definition_Model_Abstract
{

	/**
	 * @var string
	 */
	protected $iterator_class_name = __NAMESPACE__.'\DataModel_Related_MtoN_Iterator';

	/**
	 * @var string
	 */
	protected $M_model_class_name = '';

	/**
	 * @var string
	 */
	protected $M_model_name = '';

	/**
	 * @var string
	 */
	protected $N_model_class_name = '';

	/**
	 * @var string
	 */
	protected $N_model_name = '';


	/**
	 * @var array
	 */
	protected $default_order_by = [];


	/**
	 * @var DataModel_Definition_Property_Abstract[][]
	 */
	protected $relation_id_properties = [];

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[][]
	 */
	protected $join_by = [];

	/**
	 * @var array
	 */
	protected $_glue_defined = [];

	/**
	 * @var array
	 */
	protected $_related_model_class_names = [];

	/** @noinspection PhpMissingParentConstructorInspection
	 *
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function __construct( $data_model_class_name = '' )
	{
		if( $data_model_class_name ) {
			$this->_mainInit( $data_model_class_name );

			$this->_initParents();
			$this->_initBackendsConfig();
			$this->_initProperties();
			$this->_initKeys();
		}
	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents()
	{
		$data_model_class_name = $this->getClassName();

		if( ( $iterator_class_name = BaseObject_Reflection::get( $this->class_name, 'iterator_class_name', null ) ) ) {
			$this->iterator_class_name = $iterator_class_name;
		}

		if( !( $this->M_model_class_name = BaseObject_Reflection::get(
			$this->class_name, 'M_model_class_name', null
		) )
		) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:M_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		if( !( $this->N_model_class_name = BaseObject_Reflection::get(
			$this->class_name, 'N_model_class_name', null
		) )
		) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:N_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->M_model_name = $this->_getModelNameDefinition( $this->M_model_class_name );
		$this->N_model_name = $this->_getModelNameDefinition( $this->N_model_class_name );


		$this->relation_id_properties[$this->M_model_name] = [];
		$this->relation_id_properties[$this->N_model_name] = [];

		$this->join_by[$this->M_model_name] = [];
		$this->join_by[$this->N_model_name] = [];

		$this->_glue_defined[$this->M_model_name] = [];
		$this->_glue_defined[$this->N_model_name] = [];


		$this->_related_model_class_names[$this->M_model_name] = $this->M_model_class_name;
		$this->_related_model_class_names[$this->N_model_name] = $this->N_model_class_name;

	}

	/**
	 *
	 */
	protected function _initBackendsConfig()
	{
		$main_class_name = $this->M_model_class_name;

		$this->forced_backend_type = BaseObject_Reflection::get(
			$main_class_name, 'data_model_forced_backend_type', null
		);
		$this->forced_backend_config = BaseObject_Reflection::get(
			$main_class_name, 'data_model_forced_backend_config', null
		);
	}

	/**
	 *
	 */
	protected function _initProperties()
	{

		parent::_initProperties();

		foreach( $this->_glue_defined as $model_name => $glue_defined ) {

			$related_definition_data = $this->_getPropertiesDefinitionData(
				$this->_related_model_class_names[$model_name]
			);

			foreach( $related_definition_data as $main_id_property_name => $pd ) {

				if( empty( $pd['is_id'] ) ) {
					continue;
				}

				if( !in_array( $main_id_property_name, $glue_defined ) ) {
					throw new DataModel_Exception(
						'Class \''.$this->class_name.'\':  Model \''.$model_name.'\' relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \''.$model_name.'.'.$main_id_property_name.'\' ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getIteratorClassName()
	{
		return $this->iterator_class_name;
	}

	/**
	 * @param string $iterator_class_name
	 */
	public function setIteratorClassName( $iterator_class_name )
	{
		$this->iterator_class_name = $iterator_class_name;
	}

	/**
	 * @return string
	 */
	public function getMModelClassName()
	{
		return $this->M_model_class_name;
	}

	/**
	 *
	 * @return string|null
	 */
	public function getMModelName()
	{
		return $this->M_model_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract|null
	 */
	public function getMModelDefinition()
	{
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->M_model_class_name );
	}

	/**
	 * @return string
	 */
	public function getNModelClassName()
	{
		return $this->N_model_class_name;
	}

	/**
	 *
	 * @return string|null
	 */
	public function getNModelName()
	{
		return $this->N_model_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract|null
	 */
	public function getNModelDefinition()
	{
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->N_model_class_name );
	}

	/**
	 *
	 * @param DataModel_Definition_Relations $internal_relations
	 *
	 */
	public function getInternalRelations( DataModel_Definition_Relations $internal_relations )
	{

		foreach( $this->_getInternalRelations() as $key => $relation ) {
			$internal_relations[$key] = $relation;
		}
	}

	/**
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Internal[]
	 */
	public function _getInternalRelations()
	{


		/**
		 * @var DataModel_Definition_Relation_Internal[] $relations
		 */
		$relations = [];


		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $main_glue_relation_join_by
		 */
		$main_glue_relation_join_by = $this->join_by[$this->M_model_name];
		$relations[$this->getModelName()] = new DataModel_Definition_Relation_Internal(
			$this, $main_glue_relation_join_by
		);


		$N_model_definition = DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->N_model_class_name );

		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $glue_n_relation_join_by
		 */
		$glue_n_relation_join_by = $this->join_by[$this->N_model_name];

		$relations[$this->N_model_name] = new DataModel_Definition_Relation_Internal(
			$N_model_definition, $glue_n_relation_join_by
		);
		$relations[$this->N_model_name]->setRequiredRelations( [ $this->getModelName() ] );

		return $relations;
	}

	/**
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Internal
	 */
	public function getRelationToN()
	{

		$N_model_definition = DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->N_model_class_name );

		/**
		 * @var DataModel_Definition_Relation_JoinBy_Item[] $glue_n_relation_join_by
		 */
		$glue_n_relation_join_by = $this->join_by[$this->N_model_name];
		$relation = new DataModel_Definition_Relation_Internal( $N_model_definition, $glue_n_relation_join_by );


		return $relation;

	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getMRelationIdProperties()
	{
		return $this->relation_id_properties[$this->M_model_name];
	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getNRelationIdProperties()
	{
		return $this->relation_id_properties[$this->N_model_name];
	}

	/**
	 * @return array
	 */
	public function getDefaultOrderBy()
	{
		return $this->default_order_by;
	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy( $default_order_by )
	{
		$this->default_order_by = $default_order_by;
	}

	/**
	 * @param string $this_id_property_name
	 * @param string $related_to
	 * @param array  $property_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract
	 *
	 */
	protected function _initGlueProperty( $this_id_property_name, $related_to, $property_definition_data )
	{

		$related_to = explode( '.', $related_to );
		if( count( $related_to )!=2 ) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'related_model_name_m.id\', @JetDataModel:related_to=\'related_model_name_n.id\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $related_model_name, $related_to_property_name ) = $related_to;

		if( !isset( $this->_related_model_class_names[$related_model_name] ) ) {
			throw new DataModel_Exception(
				'Unknown related data model name \''.$related_model_name.'\' (in class \''.$this->class_name.'\', property: \''.$this_id_property_name.'\') ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$related_to_class_name = $this->_related_model_class_names[$related_model_name];
		$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );


		if( !isset( $related_definition_data[$related_to_property_name] ) ) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\' (in class \''.$this->class_name.'\', property: \''.$this_id_property_name.'\')',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}


		$this_id_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this->class_name, $this_id_property_name, $related_definition_data[$related_to_property_name]
		);


		$this_id_property->setUpRelation( $related_to_class_name, $related_to_property_name );

		$this->properties[$this_id_property_name] = $this_id_property;

		$this->relation_id_properties[$related_model_name][$this_id_property_name] = $this_id_property;
		$this->join_by[$related_model_name][] = new DataModel_Definition_Relation_JoinBy_Item(
			$this, $this_id_property, $related_to_class_name, $related_to_property_name
		);
		$this->_glue_defined[$related_model_name][] = $related_to_property_name;

		return $this_id_property;
	}

}
