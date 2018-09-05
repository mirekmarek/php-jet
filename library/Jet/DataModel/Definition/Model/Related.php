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
class DataModel_Definition_Model_Related extends DataModel_Definition_Model
{


	/**
	 * @var string
	 */
	protected $main_model_class_name = '';

	/**
	 *
	 * @var array
	 */
	protected $main_model_relation_id_properties = [];

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
	 *
	 * @var array
	 */
	protected $parent_model_relation_id_properties = [];

	/**
	 * @var array
	 */
	protected $default_order_by = [];


	/**
	 *
	 */
	public function init()
	{
		$this->_initParents();
		$this->_initProperties();
		$this->_initKeys();

		$this->default_order_by = Reflection::get( $this->class_name, 'default_order_by', [] );

		if( !$this->id_properties ) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents()
	{

		$parent_model_class_name = Reflection::get(
			$this->class_name,
			'data_model_parent_model_class_name'
		);

		if( !$parent_model_class_name ) {
			throw new DataModel_Exception(
				$this->class_name.' @JetDataModel:parent_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->parent_model_class_name = $parent_model_class_name;

		$main_model_class_name = $parent_model_class_name;

		// Traversing and seeking for main model
		while( ( $_parent = Reflection::get( $main_model_class_name, 'data_model_parent_model_class_name' ) ) ) {

			$main_model_class_name = $_parent;

			$this->is_sub_related_model = true;
		}

		if( !is_subclass_of( $main_model_class_name, __NAMESPACE__.'\DataModel' ) ) {
			throw new DataModel_Exception(
				'Main parent class '.$main_model_class_name.' is not subclass of '.__NAMESPACE__.'\DataModel ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->main_model_class_name = $main_model_class_name;

	}


	/**
	 *
	 */
	protected function _initProperties()
	{

		parent::_initProperties();

		$main_id_relation_defined = [];
		$parent_id_relation_defined = [];

		foreach( $this->properties as $property ) {
			if(!$property->getRelatedToClassName()) {
				continue;
			}

			if($property->getRelatedToClassName()==$this->main_model_class_name) {
				$main_id_relation_defined[] = $property->getRelatedToPropertyName();
			}

			if($property->getRelatedToClassName()==$this->parent_model_class_name) {
				$parent_id_relation_defined[] = $property->getRelatedToPropertyName();
			}
		}

		$related_definition_data = $this->_getPropertiesDefinitionData( $this->main_model_class_name );
		foreach( $related_definition_data as $property_name => $pd ) {
			if( empty( $pd['is_id'] ) ) {
				continue;
			}

			if( !in_array( $property_name, $main_id_relation_defined ) ) {
				throw new DataModel_Exception(
					'Class: \''.$this->class_name.'\'  Main model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'main.'.$property_name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

		if( $this->is_sub_related_model ) {
			$related_definition_data = $this->_getPropertiesDefinitionData( $this->parent_model_class_name );

			foreach( $related_definition_data as $property_name => $pd ) {

				if(
					empty( $pd['is_id'] ) ||
					!empty( $pd['related_to'] )
				) {
					continue;
				}

				if( !in_array( $property_name, $parent_id_relation_defined ) ) {
					throw new DataModel_Exception(
						'Class: \''.$this->class_name.'\'  parent model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'parent.'.$property_name.'\' ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}

	}

	/**
	 *
	 */
	public function initRelations()
	{
		parent::initRelations();

		$main_to_this_join = [];

		$main_model_class_name = $this->getMainModelClassName();


		foreach( $this->properties as $property ) {
			if($property->getRelatedToClassName()==$main_model_class_name) {
				$main_to_this_join[ $property->getRelatedToPropertyName() ] = $property->getName();
			}
		}



		DataModel_Relations::add(
			$main_model_class_name,
			new DataModel_Definition_Relation_Internal(
				$main_model_class_name,
				$this->class_name,
				$main_to_this_join
			)
		);


		if( $this->is_sub_related_model ) {

			$parent_to_this_join = [];

			$parent_model_class_name = $this->getParentModelClassName();
			$parent_model_definition = $this->getParentModelDefinition();


			foreach( $this->properties as $property ) {
				if($property->getRelatedToClassName()==$parent_model_class_name) {
					$parent_to_this_join[$property->getRelatedToPropertyName()] = $property->getName();
				}

				if($property->getRelatedToClassName()==$main_model_class_name) {

					foreach( $parent_model_definition->getProperties() as $parent_property ) {
						if(
							$parent_property->getRelatedToClassName()==$main_model_class_name &&
							$parent_property->getRelatedToPropertyName()==$property->getRelatedToPropertyName()
						) {
							$parent_to_this_join[ $parent_property->getName() ] = $property->getName();
							break;
						}
					}

				}
			}

			DataModel_Relations::add(
				$parent_model_class_name,

				new DataModel_Definition_Relation_Internal(
					$parent_model_class_name,
					$this->class_name,
					$parent_to_this_join
				)
			);
		}


	}


	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getMainModelRelationIdProperties()
	{
		$res = [];

		foreach( $this->main_model_relation_id_properties as $property_name ) {
			$res[$property_name] = $this->properties[$property_name];
		}

		return $res;
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getParentModelRelationIdProperties()
	{
		$res = [];

		foreach( $this->parent_model_relation_id_properties as $property_name ) {
			$res[$property_name] = $this->properties[$property_name];
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getMainModelClassName()
	{
		return $this->main_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getParentModelClassName()
	{
		return $this->parent_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModelDefinition()
	{
		return DataModel_Definition::get( $this->main_model_class_name );
	}
	/**
	 *
	 * @return DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentModelDefinition()
	{
		return DataModel_Definition::get( $this->parent_model_class_name );
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
	 * @return DataModel_Definition_Property
	 *
	 */
	protected function _initRelationProperty( $this_id_property_name, $related_to, $property_definition_data )
	{

		$related_to = explode( '.', $related_to );

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
				$what!='main'
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

		if( !isset( $related_definition_data[$related_to_property_name] ) ) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_id_property_data = $related_definition_data[$related_to_property_name];

		$parent_id_property_data['is_key'] = true;
		$parent_id_property_data['is_id'] = true;


		if( isset( $property_definition_data['form_field_type'] ) ) {
			$parent_id_property_data['form_field_type'] = $property_definition_data['form_field_type'];
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
}