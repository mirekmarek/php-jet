<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class DataModel_Definition_Model_Related_MtoN extends DataModel_Definition_Model_Related
{

	/**
	 * @var string
	 */
	protected $iterator_class_name = __NAMESPACE__.'\DataModel_Related_MtoN_Iterator';

	/**
	 * @var string
	 */
	protected $N_model_class_name = '';

	/**
	 * @var string
	 */
	protected $N_model_name = '';

	/**
	 *
	 * @var array
	 */
	protected $N_model_relation_id_properties = [];

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
	 * @throws DataModel_Exception
	 */
	protected function _initParents()
	{
		parent::_initParents();

		if( ( $iterator_class_name = Reflection::get( $this->class_name, 'iterator_class_name', null ) ) ) {
			$this->iterator_class_name = $iterator_class_name;
		}

		$data_model_class_name = $this->getClassName();


		if(
			!( $this->N_model_class_name = Reflection::get( $this->class_name, 'N_model_class_name', null ) )
		) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:N_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}
		$this->N_model_name = $this->_getModelNameDefinition( $this->N_model_class_name );
	}


	/**
	 *
	 */
	protected function _initProperties()
	{

		parent::_initProperties();

		$N_id_relation_defined = [];

		foreach( $this->properties as $property ) {
			if(!$property->getRelatedToClassName()) {
				continue;
			}

			if($property->getRelatedToClassName()==$this->N_model_class_name) {
				$N_id_relation_defined[] = $property->getRelatedToPropertyName();
			}
		}

		$N_related_definition_data = $this->_getPropertiesDefinitionData( $this->N_model_class_name );

		foreach( $N_related_definition_data as $property_name => $pd ) {
			if( empty( $pd['is_id'] ) ) {
				continue;
			}

			if( !in_array( $property_name, $N_id_relation_defined ) ) {
				throw new DataModel_Exception(
					'Class: \''.$this->class_name.'\'  N model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \''.$this->N_model_name.'.'.$property_name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}


	/**
	 * @param string $this_id_property_name
	 * @param string $related_to
	 * @param array  $property_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property
	 *
	 */
	protected function _initRelationProperty( $this_id_property_name, $related_to, $property_definition_data )
	{

		$related_to = explode( '.', $related_to );
		$N_model_name = $this->getNModelName();

		if( count( $related_to )!=2 ) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.id\', @JetDataModel:related_to=\'main.id\', class:'.$this->class_name,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $what, $related_to_property_name ) = $related_to;

		if(
			(
				$what!='parent' &&
				$what!='main' &&
				$what!=$N_model_name
			) ||
			!$related_to_property_name
		) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.id\', @JetDataModel:related_to=\'main.id\', class:'.$this->class_name,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		if( !$this->is_sub_related_model && $what=='parent' ) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to = \'parent.'.$related_to_property_name.'\' definition. Use: @JetDataModel:related_to = \'main.'.$related_to_property_name.'\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$related_to_class_name = '';

		$related_definition_data = [];

		$relation_to_N = false;

		if( $what=='parent' ) {
			$related_to_class_name = $this->parent_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->parent_model_relation_id_properties;
		}

		if( $what=='main' ) {
			$related_to_class_name = $this->main_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->main_model_relation_id_properties;
		}

		if( $what==$N_model_name ) {
			$related_to_class_name = $this->N_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->N_model_relation_id_properties;
			$relation_to_N = true;
		}

		if( !isset( $related_definition_data[$related_to_property_name] ) ) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_id_property_data = $related_definition_data[$related_to_property_name];

		$parent_id_property_data['is_key'] = true;
		$parent_id_property_data['is_id'] = true;


		if(!$relation_to_N) {
			$parent_id_property_data['form_field_type'] = false;
		} else {
			if( isset( $property_definition_data['form_field_type'] ) ) {
				$parent_id_property_data['form_field_type'] = $property_definition_data['form_field_type'];
			}
		}

		$this_id_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this->class_name,
			$this_id_property_name,
			$parent_id_property_data
		);

		$this_id_property->setUpRelation( $related_to_class_name, $related_to_property_name );


		$this->properties[$this_id_property_name] = $this_id_property;

		$target_properties_array[] = $this_id_property_name;

		return $this_id_property;

	}


	/**
	 *
	 */
	public function initRelations()
	{
		parent::initRelations();

		$this_to_N_join = [];

		foreach( $this->properties as $property ) {
			if($property->getRelatedToClassName()==$this->N_model_class_name) {
				$this_to_N_join[$property->getName()] = $property->getRelatedToPropertyName();
			}
		}

		DataModel_Relations::add(
			$this->class_name,
			new DataModel_Definition_Relation_Internal(
				$this->class_name,
				$this->N_model_class_name,
				$this_to_N_join
			)
		);


		DataModel_Relations::add(
			$this->getMainModelClassName(),
			new DataModel_Definition_Relation_Internal(
				$this->class_name,
				$this->N_model_class_name,
				$this_to_N_join,
				[$this->model_name]
			)
		);



		if( $this->is_sub_related_model ) {
			DataModel_Relations::add(
				$this->getParentModelClassName(),
				new DataModel_Definition_Relation_Internal(
					$this->class_name,
					$this->N_model_class_name,
					$this_to_N_join,
					[$this->model_name]
				)
			);
		}

		$N_relations = $this->getNModelDefinition()->getRelations();

		foreach( $N_relations as $_N_relation ) {

			$N_relation = clone $_N_relation;

			$required = $N_relation->getRequiredRelations();
			array_unshift($required, $this->getNModelName());
			array_unshift($required, $this->model_name);

			$N_relation->setRequiredRelations( $required );

			DataModel_Relations::add(
				$this->getMainModelClassName(),
				$N_relation,
				true
			);

		}


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
	 * @return DataModel_Definition_Model|null
	 */
	public function getNModelDefinition()
	{
		return DataModel_Definition::get( $this->N_model_class_name );
	}

	/**
	 * @return array
	 */
	public function getNModelRelationIdProperties()
	{
		$res = [];

		foreach( $this->N_model_relation_id_properties as $property_name ) {
			$res[$property_name] = $this->properties[$property_name];
		}

		return $res;
	}
}
