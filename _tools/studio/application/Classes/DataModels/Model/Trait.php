<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Interface;
use Jet\Tr;
use Jet\DataModel;
use Jet\DataModel_Definition_Key;
use Jet\DataModel_Definition_Relation_Join_Item;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;


trait DataModels_Model_Trait
{

	/**
	 * @var string
	 */
	protected $namespace_id = Project_Namespace::APPLICATION_NS_ID;

	/**
	 * @var bool
	 */
	protected $is_abstract = false;

	/**
	 * @var string
	 */
	protected $internal_id = '';

	/**
	 * @var array
	 */
	protected $internal_children_ids = [];

	/**
	 * @var string
	 */
	protected $extends = '';

	/**
	 * @var array
	 */
	protected $implements = [];

	/**
	 * @var DataModels_OuterRelation[]
	 */
	protected $outer_relations = [];

	/**
	 * @var Form
	 */
	protected $__edit_form;


	/**
	 * @var Form
	 */
	protected $__sort_properties_form;

	/**
	 * @var ClassCreator_Class
	 */
	protected $__class;


	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Model_Interface $model
	 */
	public static function createByParser_properties( DataModels_Parser_Class $class, DataModels_Model_Interface $model )
	{

		foreach( $class->getProperties() as $property ) {

			$property = static::createByParser_property( $class, $property );

			if($property) {
				$model->addProperty( $property );
			}
		}
	}

	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Parser_Class_Property $property
	 *
	 * @return DataModels_Property_Interface|null
	 */
	public static function createByParser_property( DataModels_Parser_Class $class, DataModels_Parser_Class_Property $property)
	{
		if(!isset($property->getParameters()['type'])) {
			if(isset($property->getParameters()['related_to'])) {
				$type = 'RelatedTmp';
			} else {
				return null;
			}
		} else {
			$type = $property->getParameters()['type']->getValue();
		}


		$class_name = __NAMESPACE__.'\\DataModels_Property_'.$type;

		/**
		 * @var DataModels_Property_Interface $class_name
		 */
		$property = $class_name::createByParser( $class, $property );

		return $property;
	}

	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Model_Interface $model
	 * @param DataModels_Parser_Parameter $param
	 *
	 */
	public static function createByParser_keys( DataModels_Parser_Class $class, DataModels_Model_Interface $model, DataModels_Parser_Parameter $param )
	{
		$keys = $param->getValue();

		foreach( $keys as $k_d ) {
			$exists = false;

			foreach( $model->getKeys() as $e_key ) {
				if($e_key->getName()==$k_d['name']) {
					$exists = true;
					break;
				}

			}

			if(!$exists) {
				$key = new DataModels_Key();
				$key->setName( $k_d['name'] );
				$key->setType( $k_d['type'] );
				$key->setPropertyNames( $k_d['property_names'] );

				$model->addNewKey( $key );
			}
		}
	}

	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Model_Interface $model
	 * @param DataModels_Parser_Parameter $param
	 */
	public static function createByParser_relations( DataModels_Parser_Class $class, DataModels_Model_Interface $model, DataModels_Parser_Parameter $param )
	{
		$relations = $param->getValue();

		foreach( $relations as $r_d ) {

			$relation = new DataModels_OuterRelation();

			$join = [];

			foreach( $r_d['join_by_properties'] as $t_j=>$r_j ) {
				$join[$t_j] = $t_j;
			}

			$relation->setRelatedToClass( $r_d['related_to_class_name'] );
			$relation->setJoinType( $r_d['join_type'] );
			$relation->setJoinBy( $join );
			$relation->setRequiredRelations( $r_d['required_relations'] );

			$model->addOuterRelation( $relation );
		}
	}



	/**
	 *
	 */
	public function __construct()
	{
		$this->internal_id = uniqid();
	}

	/**
	 * @return string
	 */
	public function getNamespaceId()
	{
		return $this->namespace_id;
	}

	/**
	 * @param string $namespace_id
	 */
	public function setNamespaceId($namespace_id)
	{
		$this->namespace_id = $namespace_id;

		foreach($this->getChildren() as $ch) {
			$ch->setNamespaceId( $namespace_id );
		}
	}

	/**
	 * @return string
	 */
	public function getInternalType()
	{
		return $this->internal_type;
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		/**
		 * @var DataModels_Model $this
		 */
		return $this->internal_id;
	}

	/**
	 * @return bool
	 */
	public function isAbstract()
	{
		return $this->is_abstract;
	}

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( $is_abstract )
	{
		$this->is_abstract = $is_abstract;
	}


	/**
	 * @return string
	 */
	public function getExtends()
	{
		return $this->extends;
	}

	/**
	 * @param string $extends
	 * @param bool $handle_inheritance
	 */
	public function setExtends( $extends, $handle_inheritance=true )
	{
		if($extends==$this->extends) {
			return;
		}

		if(!$handle_inheritance) {
			$this->extends = $extends;

			return;
		}

		$old_extends = $this->extends;
		$new_extends = $extends;

		if($old_extends) {
			foreach( $this->getProperties() as $property ) {
				/**
				 * @var DataModels_Property $property
				 */
				if(
					!$property->isInherited() ||
					$property->getInheritedModelId()!=$old_extends
				) {
					continue;
				}

				$this->removeProperty( $property->getInternalId() );
			}
		}

		if( $new_extends ) {

			$extends_model = DataModels::getModel( $new_extends );

			if($extends_model) {
				$this->setModelName( $extends_model->getModelName() );
				$this->setIDControllerClassName( $extends_model->getIDControllerClassName() );
				$this->setDatabaseTableName( $extends_model->getDatabaseTableName() );


				foreach( $extends_model->getProperties() as $e_property ) {
					foreach($this->getProperties() as $property) {
						if($property->getName()==$e_property->getName()) {
							$this->removeProperty( $property->getInternalId() );
						}
					}
				}


				foreach( $extends_model->getProperties() as $property ) {
					$new_property = clone $property;

					$new_property->setIsInherited( true );
					$new_property->setInheritedPropertyId( $property->getInternalId() );
					$new_property->setInheritedModelId( $extends_model->getInternalId() );

					$this->addProperty( $new_property );
				}
			}

			$this->checkSortOfProperties();
		}

		$this->extends = $extends;
	}

	/**
	 * @return array
	 */
	public function getExtendsPath()
	{
		$res = [];

		$getParent = function( DataModels_Model_Interface $model ) use (&$res, &$getParent) {
			if($model->getExtends()) {
				$e_model = DataModels::getModel( $model->getExtends() );
				if($e_model) {
					$res[] = $e_model->getInternalId();
					$getParent( $e_model );
				}
			}

		};

		/** @noinspection PhpParamsInspection */
		$getParent( $this );

		return $res;
	}

	/**
	 * @return array
	 */
	public function getExtendsScope()
	{
		$extends_scope = [
			'' => '- default -',
		];

		foreach( DataModels::getModels() as $e_model ) {
			/** @noinspection PhpParamsInspection */
			if(
				get_class($this)!=get_class($e_model) ||
				$this->getInternalId()==$e_model->getInternalId() ||
				$e_model->isDescendantOf( $this )
			) {
				continue;
			}

			$extends_scope[$e_model->getInternalId()] = $e_model->getFullClassName();
		}


		return $extends_scope;
	}

	/**
	 * @return array
	 */
	public function getImplements()
	{
		return $this->implements;
	}

	/**
	 * @param array $implements
	 */
	public function setImplements( array $implements )
	{
		$this->implements = $implements;
	}




	/**
	 * @param DataModels_Model_Interface $model
	 *
	 * @return bool
	 */
	public function isDescendantOf( DataModels_Model_Interface $model )
	{
		$parents = $this->getExtendsPath();

		return in_array( $model->getInternalId(), $parents );
	}


	/**
	 * @return string
	 */
	public function getModelName()
	{
		/**
		 * @var DataModels_Model $this
		 */
		return $this->model_name;
	}

	/**
	 * @param string $model_name
	 */
	public function setModelName($model_name)
	{
		/**
		 * @var DataModels_Model $this
		 */
		if($this->model_name==$model_name) {
			return;
		}

		$old_value = $this->model_name;
		$len = strlen($old_value);

		$this->model_name = $model_name;

		foreach( $this->getChildren() as $child ) {
			if(substr($child->getModelName(), 0, $len)==$old_value) {
				$child->setModelName( $model_name.substr($child->getModelName(), $len) );
			}
		}

		foreach( $this->getChildren() as $ch ) {
			$ch->regenerateRelatedPropertyNames();
		}
	}

	/**
	 * @return string
	 */
	public function getDatabaseTableName()
	{
		/**
		 * @var DataModels_Model $this
		 */
		return $this->database_table_name;
	}

	/**
	 * @param string $database_table_name
	 */
	public function setDatabaseTableName($database_table_name)
	{
		/**
		 * @var DataModels_Model $this
		 */
		$this->database_table_name = $database_table_name;
	}

	/**
	 * @param string $id_controller_class_name
	 */
	public function setIDControllerClassName( $id_controller_class_name )
	{
		$this->id_controller_class_name = $id_controller_class_name;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		/**
		 * @var DataModels_Model $this
		 */
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getFullClassName()
	{
		return Project::getNamespace($this->getNamespaceId())->getNamespace().'\\'.$this->getClassName();
	}

	/**
	 * @return DataModels_Model_Id_Abstract|null
	 */
	public function getIDControllerDefinition()
	{
		/**
		 * @var DataModels_Model $this
		 */

		if(!$this->getIDControllerClassName()) {
			return null;
		}

		$class_name = __NAMESPACE__.'\DataModels_Model_Id_'.str_replace('Jet\DataModel_IDController_', '', $this->getIDControllerClassName());

		return new $class_name( $this );
	}


	/**
	 * @param string $class_name
	 */
	public function setClassName($class_name)
	{
		/**
		 * @var DataModels_Model $this
		 */
		$old_value = $this->class_name;
		$len = strlen($old_value);


		$this->class_name = $class_name;

		foreach( $this->getChildren() as $child ) {
			if(substr($child->getClassName(), 0, $len)==$old_value) {
				$child->setClassName( $class_name.substr($child->getClassName(), $len) );
			}
		}

	}

	/**
	 * @param DataModels_Model_Related_Interface $child
	 */
	public function addChild(DataModels_Model_Related_Interface $child )
	{
		$id = $child->getInternalId();

		if(!in_array($id, $this->internal_children_ids)) {
			$this->internal_children_ids[] = $id;
		}
	}

	/**
	 * @return array
	 */
	public function getChildrenIds()
	{
		return $this->internal_children_ids;
	}

	/**
	 * @param array $internal_children_ids
	 */
	public function setInternalChildrenIds( $internal_children_ids )
	{
		$this->internal_children_ids = $internal_children_ids;
	}




	/**
	 * @param DataModels_Model_Related_Interface $child
	 */
	public function removeChild(DataModels_Model_Related_Interface $child )
	{
		$id = $child->getInternalId();

		$i = array_search($id, $this->internal_children_ids);
		if($i!==false) {
			unset( $this->internal_children_ids[$i] );
		}

		foreach( $this->properties as $id=>$property ) {

			/**
			 * @var DataModels_Property_Interface $property
			 */
			if($property->getDataModelClassName()==$child->getInternalId()) {
				unset( $this->properties[$id] );
			}
		}

	}


	/**
	 *
	 * @return DataModels_Model_Related_1to1[]|DataModels_Model_Related_1toN[]|DataModels_Model_Related_MtoN[]
	 */
	public function getChildren()
	{
		$children = [];

		foreach( $this->getChildrenIds() as $id ) {
			$model = DataModels::getModel( $id );
			if($model) {
				$children[$id] = $model;
			}
		}

		return $children;
	}

	/**
	 * @return Form
	 */
	public function getEditForm()
	{

		if(!$this->__edit_form) {

			$is_abstract_filed = new Form_Field_Checkbox('is_abstract', 'Class is abstract', $this->is_abstract);
			$is_abstract_filed->setCatcher( function($value) {
				$this->setIsAbstract( $value );
			} );

			$model_name_field = new Form_Field_Input('model_name', 'Model name:', $this->model_name);
			$model_name_field->setIsRequired(true);
			$model_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel name format'
			]);
			$model_name_field->setCatcher( function( $value ) {
				$this->setModelName( $value );
			} );
			$model_name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModels_Model::checkModelName( $field, $this );
			} );



			$class_name_field = new Form_Field_Input('class_name', 'Class name:', $this->class_name);
			$class_name_field->setIsRequired(true);
			$class_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel name format'
			]);
			$class_name_field->setCatcher( function( $value ) {
				$this->setClassName($value);
			} );
			$class_name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModels_Model::checkClassName( $field, $this );
			} );




			$extends_field = new Form_Field_Select('extends', 'Extends:', $this->extends);
			$extends_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select class'
			]);
			$extends_field->setCatcher( function($value) {
				$this->setExtends( $value );
			} );

			$extends_field->setSelectOptions($this->getExtendsScope());




			$implements_field = new Form_Field_Textarea('implements', 'Implements:', implode("\n", $this->implements));
			$implements_field->setCatcher( function($value) {
				$value = explode("\n", $value);

				$this->implements = [];
				foreach( $value as $i=>$v ) {
					$v = trim( $v );
					if($v) {
						$this->implements[] = $v;
					}
				}

			} );





			$database_table_name_field = new Form_Field_Input('database_table_name', 'Custom table name:', $this->database_table_name);
			$database_table_name_field->setCatcher( function( $value ) {
				$this->setDatabaseTableName( $value );
			} );
			$database_table_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel table name name format'
			]);
			$database_table_name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModels_Model::checkTableName( $field, $this );
			} );




			$id_controller_class_field = new Form_Field_Select('id_controller_class', 'ID controller class: ', $this->getIDControllerClassName() );
			$id_controller_class_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ID controller class'
			]);
			$id_controller_class_field->setCatcher( function( $value ) {
				$this->setIDControllerClassName( $value );
			} );
			$id_controller_class_field->setSelectOptions(
				DataModels_Model::getIDControllers()
			);

			$fields = [
				$is_abstract_filed,
				$model_name_field,
				$class_name_field,
				$extends_field,
				$implements_field,
				$database_table_name_field,
				$id_controller_class_field
			];


			if($this->getIDControllerDefinition()) {
				$id_option_fields = $this->getIDControllerDefinition()->getOptionsFormFields();
				foreach( $id_option_fields as $field ) {
					$field->setName('/id_controller_options/'.$field->getName());
					$fields[] = $field;
				}
			}


			if( $this instanceof DataModels_Model) {
				$namespace_field = new Form_Field_Select('namespace_id', 'Namespace:', $this->namespace_id);
				$namespace_field->setErrorMessages([
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select namespace'
				]);
				$fields[] = $namespace_field;

				$namespaces = [];

				foreach( Project::getNamespaces() as $ns ) {
					if($ns->isInternal()) {
						continue;
					}

					$namespaces[$ns->getId()] = $ns->getLabel();
				}
				$namespace_field->setSelectOptions($namespaces);

				$namespace_field->setCatcher( function( $value ) {
					$this->setNamespaceId( $value );
				} );
			}

			if(
				$this instanceof DataModels_Model_Related_1toN
				||
				$this instanceof DataModels_Model_Related_MtoN
			)  {

				$iterator_class_name_field = new Form_Field_Input('iterator_class_name', 'Iterator class:', $this->getIteratorClassName());
				$iterator_class_name_field->setCatcher( function( $value ) {
					$this->setIteratorClassName( $value );
				} );
				$iterator_class_name_field->setIsRequired( true );
				$iterator_class_name_field->setValidationRegexp( '/^[a-z0-9\\\\\_]{2,}$/i' );
				$iterator_class_name_field->setErrorMessages([
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter iterator class name',
					Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid iterator class name name format'
				]);

				$fields[$iterator_class_name_field->getName()] = $iterator_class_name_field;
			}


			if(
				$this instanceof DataModels_Model_Related_1toN
				||
				$this instanceof DataModels_Model_Related_MtoN
			) {
				$default_order_by_field = new Form_Field_Hidden( 'default_order_by', '', implode('|', $this->getDefaultOrderBy()) );
				$default_order_by_field->setCatcher( function( $value ) {
					if(!$value) {
						$value = [];
					} else {
						$value = explode('|', $value);
					}
					$this->setDefaultOrderBy( $value );
				} );

				$fields[$default_order_by_field->getName()] = $default_order_by_field;
			}



			$this->__edit_form = new Form('edit_model_form', $fields );
			$this->__edit_form->setAction( DataModels::getActionUrl('edit') );

		}

		return $this->__edit_form;
	}

	/**
	 *
	 */
	public function showEditFormFields()
	{
		$form = $this->getEditForm();


		if($form->fieldExists('namespace_id')) {
			echo $form->field('namespace_id');
		}

		$form->field('model_name')->input()->addJsAction('onkeydown', "JetStudio.DataModel.generateClassName(this.value, '".$form->field('class_name')->getId()."')");


		echo $form->field('model_name');
		echo $form->field('class_name');
		if($form->fieldExists('is_abstract')) {
			echo $form->field('is_abstract');
		}
		echo $form->field('extends');

		$form->field('implements')->input()->addCustomCssStyle('height:100px');
		echo $form->field('implements');

		echo $form->field('database_table_name');
		echo $form->field('id_controller_class');


		if(
			$this->getIDControllerDefinition() &&
			($id_controller_options = $this->getIDControllerDefinition()->getOptionsList())
		) {
			?>
			<legend><?=Tr::_('ID controller options')?></legend>
			<?php
			foreach( $id_controller_options as $id_option ) {
				echo $form->field('/id_controller_options/'.$id_option);
			}
		}

		if($form->fieldExists('iterator_class_name')) {
			echo $form->field('iterator_class_name');
		}

		if($form->fieldExists('default_order_by')) {


			/**
			 * @var DataModels_Model_Related_MtoN|DataModels_Model_Related_1toN $this
			 */
			$order_by_options = $this->getOrderByOptions();

			if($order_by_options) {

				$view = Application::getView();
				$view->setVar('form', $form);
				$view->setVar('order_by_options', $order_by_options);

				echo $view->render('data_model/model_edit/default_order_by');
			}

		}
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();

		DataModels::check();

		return true;
	}


	/**
	 * @return Form
	 */
	public function getSortPropertiesForm()
	{

		if(!$this->__sort_properties_form) {

			$fields = [];

			foreach( $this->getProperties() as $property ) {
				$sort = new Form_Field_Hidden('p_'.$property->getInternalId(), '', $property->getInternalPriority());
				$sort->setCatcher( function( $value ) use ($property) {
					$property->setInternalPriority( (int)$value );
				} );

				$fields[] = $sort;
			}

			$this->__sort_properties_form = new Form('sort_properties_form', $fields );
			$this->__sort_properties_form->setAction( DataModels::getActionUrl('property/sort') );
		}

		return $this->__sort_properties_form;
	}

	/**
	 * @return bool
	 */
	public function catchSortPropertiesForm()
	{
		$form = $this->getSortPropertiesForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();
		$this->checkSortOfProperties();

		return true;
	}

	/**
	 *
	 */
	public function showSortPropertiesForm()
	{
		$form = $this->getSortPropertiesForm();

		echo $form->start();
		foreach( $this->getProperties() as $property ) {
			echo $form->field('p_'.$property->getInternalId());
		}
		echo $form->end();
	}


	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function addProperty(DataModels_Property_Interface $property )
	{
		if(!$property->getInternalPriority()) {
			foreach( $this->properties as $o_p ) {
				/**
				 * @var DataModels_Property_Interface $o_p
				 */
				if($o_p->getInternalPriority()>$property->getInternalPriority()) {
					$property->setInternalPriority( $o_p->getInternalPriority()+1 );
				}
			}
		}

		$this->properties[$property->getInternalId()] = $property;

		foreach( DataModels::getModels() as $model ) {
			if( $model->isDescendantOf( $this ) ) {

				$new_property = clone $property;

				$new_property->setIsInherited( true );
				$new_property->setInheritedPropertyId( $property->getInternalId() );
				$new_property->setInheritedModelId( $this->getInternalId() );

				$model->addProperty( $new_property );
			}
		}

		$this->__sort_properties_form = null;
	}


	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function replaceProperty(DataModels_Property_Interface $property )
	{
		$this->properties[$property->getInternalId()] = $property;
	}


	/**
	 * @param string $property_id
	 */
	public function removeProperty( $property_id )
	{
		unset( $this->properties[$property_id] );
	}


	/**
	 * @param string $property_id
	 */
	public function deleteProperty( $property_id )
	{
		foreach( $this->getKeys() as $key ) {
			$key->removeProperty( $property_id );
			if(!$key->getPropertyNames()) {
				$this->deleteKey( $key->getInternalId() );
			}
		}

		foreach( DataModels::getModels() as $model ) {
			foreach( $model->getProperties() as $property ) {
				if(
					$property->getRelatedToPropertyName()==$property_id ||
					$property->getInheritedPropertyId()==$property_id
				) {
					$model->removeProperty( $property->getInternalId() );
				}
			}
		}

		$this->removeProperty( $property_id );

		DataModels::check();
	}

	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function propertyUpdated( DataModels_Property_Interface  $property )
	{
		foreach( DataModels::getModels() as $model ) {
			if( $model->isDescendantOf( $this ) ) {

				foreach( $model->getProperties() as $c_property ) {

					if(
						!$c_property->isInherited() ||
						$c_property->getInheritedModelId()!=$this->getInternalId() ||
						$c_property->getInheritedPropertyId()!=$property->getInternalId()
					) {
						continue;
					}

					if(!$c_property->isOverload()) {
						$model->removeProperty( $c_property->getInternalId() );

						$new_property = clone $property;

						$new_property->setIsInherited( true );
						$new_property->setInheritedPropertyId( $property->getInternalId() );
						$new_property->setInheritedModelId( $this->getInternalId() );
						$new_property->setInternalPriority( $c_property->getInternalPriority() );

						$model->addProperty( $new_property );

					}

					break;
				}
			}
		}
	}


	/**
	 * @return DataModels_Property_Interface[]
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * @param DataModels_Key $key
	 */
	public function addNewKey( DataModels_Key $key )
	{
		$this->keys[ $key->getInternalId() ] = $key;
	}

	/**
	 * @param string $key_id
	 *
	 * @return DataModels_Key|null
	 */
	public function getKey( $key_id )
	{
		if(!isset($this->keys[ $key_id ])) {
			return null;
		}

		return $this->keys[ $key_id ];
	}

	/**
	 * @return DataModels_Key[]
	 */
	public function getKeys()
	{
		return $this->keys;
	}

	/**
	 * @param string $key_id
	 */
	public function deleteKey( $key_id )
	{
		unset( $this->keys[$key_id] );
	}


	/**
	 * @param DataModels_OuterRelation $relation
	 */
	public function addOuterRelation( DataModels_OuterRelation $relation )
	{
		$this->outer_relations[ $relation->getInternalId() ] = $relation;
	}

	/**
	 * @param string $relation_id
	 *
	 * @return DataModels_OuterRelation|null
	 */
	public function getOuterRelation( $relation_id )
	{
		if(!isset($this->outer_relations[ $relation_id ])) {
			return null;
		}

		return $this->outer_relations[ $relation_id ];
	}

	/**
	 * @return DataModels_OuterRelation[]
	 */
	public function getOuterRelations()
	{
		return $this->outer_relations;
	}

	/**
	 * @param string $relation_id
	 */
	public function deleteOuterRelation( $relation_id )
	{
		unset( $this->outer_relations[$relation_id] );
	}




	/**
	 *
	 */
	public function delete()
	{

		foreach( $this->internal_children_ids as $i=>$id ) {
			if(($ch=DataModels::getModel( $id ))) {
				$ch->delete();
			}

			unset($this->internal_children_ids[$i]);
		}


		DataModels::deleteModel( $this->getInternalId() );
	}


	/**
	 *
	 */
	public function checkSortOfProperties()
	{
		$sort = function(
			DataModels_Property_Interface $a,
			DataModels_Property_Interface $b
		) {
			if($a->getInternalPriority()==$b->getInternalPriority()) {
				return 0;
			}

			if($a->getInternalPriority()<$b->getInternalPriority()) {
				return -1;
			}

			return 1;
		};

		uasort( $this->properties, $sort );

		$i = 0;
		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			if(
				!$property->getRelatedToClassName() ||
				substr($property->getRelatedToClassName(),0, 5)!='main:'
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );


		$i = 100;
		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			if(
				!$property->getRelatedToClassName() ||
				substr($property->getRelatedToClassName(),0, 7)!='parent:'
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );



		$i = 200;
		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			if(
				$property->getRelatedToClassName() ||
				!$property->getIsId()
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );



		$i = 400;
		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			if(
				$property->getRelatedToClassName() ||
				$property->getIsId() ||
				$property->getDataModelClassName()
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );

		$i = 500;
		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			if(
				$property->getRelatedToClassName() ||
				$property->getIsId() ||
				!$property->getDataModelClassName()
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );

	}

	/**
	 * @return bool
	 */
	public function canHaveRelated()
	{
		return true;
	}


	/**
	 *
	 */
	public function checkOuterRelations()
	{
		$model = $this;
		foreach( $this->outer_relations as $id=>$r ) {
			$related = $r->getRelatedDataModel();
			if(!$related) {
				unset($this->outer_relations[$id]);
				continue;
			}

			$join = [];
			$changed = false;
			foreach( $r->getJoinBy() as $j_b ) {
				if(
					!$model->hasProperty( $j_b->getThisPropertyName() ) ||
					!$related->hasProperty( $j_b->getRelatedClassName() )
				) {
					$changed = true;
					continue;
				}

				$join[] = $j_b;
			}

			if($changed) {
				$r->setJoinBy( $join );
			}
		}
	}


	/**
	 *
	 * @param string $option
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getIDControllerOption( $option, $default_value )
	{
		if(empty( $this->id_controller_options[$option])) {
			$this->id_controller_options[$option] = $default_value;
			return $default_value;
		}

		return $this->id_controller_options[$option];
	}

	/**
	 *
	 * @param string $option
	 * @param mixed $value
	 */
	public function setIDControllerOption( $option, $value )
	{
		$this->id_controller_options[$option] = $value;
	}


	/**
	 * @return string
	 */
	public function getClassPath()
	{
		$namespace = Project::getNamespace( $this->getNamespaceId() );

		$path = $namespace->getRootDirPath().str_replace('_','/', $this->getClassName()).'.php';

		return $path;
	}

	/**
	 * @return ClassCreator_Class|null
	 */
	public function createClass()
	{
		if(!$this->__class) {
			$class = $this->createClass_initClass();

			$this->createClass_main( $class );
			$this->createClass_ID( $class );
			$this->createClass_customKeys( $class );
			$this->createClass_outerRelations( $class );
			$this->createClass_properties( $class );
			$this->createClass_methods( $class );


			$dm = new ClassCreator_ActualizeDecisionMaker();

			$remove_getters = [];
			$remove_setters = [];

			$dm->update_class_annotation = function() {
				return true;
			};

			$dm->update_property = function( ClassCreator_Class_Property $new_property, ClassParser_Class_Property $current_property ) {
				return true;
			};

			$dm->remove_property = function( ClassParser_Class_Property $property ) use (&$remove_getters, &$remove_setters) {

				if(
					$property->doc_comment &&
					strpos($property->doc_comment->text, '@JetDataModel:')
				) {
					$method_name = DataModels_Property::generateSetterGetterMethodName( $property->name );

					$remove_getters[] = 'get'.$method_name;
					$remove_setters[] = 'set'.$method_name;

					return true;
				}

				return false;
			};

			$dm->remove_method = function( ClassParser_Class_Method $method ) use (&$remove_getters, &$remove_setters) {
				if(
					in_array(  $method->name, $remove_setters) ||
					in_array(  $method->name, $remove_getters)
				) {
					return true;
				}

				return false;
			};

			$class->setActualizeDecisionMaker( $dm );

			$this->__class = $class;
		}


		return $this->__class;
	}

	/**
	 * @param ClassCreator_Class $class
	 * @param string $default
	 *
	 * @return string
	 */
	public function createClass_getExtends( ClassCreator_Class $class, $default )
	{
		if(!$this->extends) {
			return $default;
		}

		$extends = $this->extends;

		$model = DataModels::getModel( $this->extends );
		if($model) {
			return $model->getFullClassName();
		}

		$use = ClassCreator_UseClass::createByClassName( $extends );

		if($use->getNamespace()!=$class->getNamespace()) {
			$class->addUse( $use );
		}

		return $use->getClass();
	}


	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{
		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;


		$class = new ClassCreator_Class();
		$project_namespace = Project::getNamespace( $model->getNamespaceId() );

		$class->setNamespace( $project_namespace->getNamespace() );
		$class->setName( $model->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->setExtends( $this->createClass_getExtends($class, 'DataModel') );

		if($this->implements) {
			foreach( $this->implements as $i ) {
				$use = ClassCreator_UseClass::createByClassName($i);
				$class->addUse( $use );

				$class->addImplements( $use->getClass() );
			}
		}

		return $class;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class )
	{
		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'name', var_export($this->getModelName(), true)) )
		);

		if($model->getDatabaseTableName()) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getDatabaseTableName(), true)) )
			);
		} else {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getModelName(), true)) )
			);
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_ID( ClassCreator_Class $class )
	{
		if($this->getIDControllerDefinition()) {
			$this->getIDControllerDefinition()->createClassIdDefinition( $class );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_customKeys( ClassCreator_Class $class )
	{
		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;

		foreach( $model->getKeys() as $key ) {
			$class->addAnnotation( $key->getAsAnnotation( $class ) );
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_outerRelations( ClassCreator_Class $class )
	{
		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;

		foreach( $model->getOuterRelations() as $relation ) {
			$class->addAnnotation( $relation->getAsAnnotation( $class ) );
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_properties( ClassCreator_Class $class )
	{
		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			if($class->hasProperty($property->getName())) {
				$class->addError('Duplicit property '.$property->getName());
				continue;
			}
			$class->addProperty( $property->createClassProperty( $class ) );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_methods( ClassCreator_Class $class )
	{

		/**
		 * @var DataModels_Model $model
		 */
		$model = $this;

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			$property->createClassMethods( $class );
		}

		if( ($id_controller_definition=$this->getIDControllerDefinition()) ) {
			$id_controller_definition->createClassMethods( $class );
		}
	}

	/**
	 *
	 */
	public function prepare()
	{
		if(!$this->database_table_name) {
			$this->database_table_name = $this->getModelName();
		}

		$this->id_properties = [];

		$properties = [];

		foreach( $this->properties as $property ) {
			/**
			 * @var DataModels_Property_Interface $property
			 */
			$property->prepare();


			if($property->getIsId()) {
				$this->id_properties[] = $property->getName();
			}

			$properties[$property->getName()] = $property;
		}

		$this->properties = $properties;

		foreach( $this->properties as $property_name => $property_definition ) {
			/**
			 * @var DataModels_Property_Interface $property_definition
			 */

			if( $property_definition->getIsKey() ) {
				$this->keys[$property_name] = new DataModel_Definition_Key(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					[ $property_name ]
				);
			}
		}

		if( $this->id_properties ) {
			$key_name = $this->model_name.'_pk';

			$this->keys[$key_name] = new DataModel_Definition_Key(
				$key_name,
				DataModel::KEY_TYPE_PRIMARY,
				$this->id_properties
			);

		}

	}

}