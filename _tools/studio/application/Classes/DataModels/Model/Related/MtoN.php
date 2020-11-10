<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Related_MtoN;
use Jet\DataModel;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Textarea;
use Jet\Tr;

class DataModels_Model_Related_MtoN extends DataModel_Definition_Model_Related_MtoN implements DataModels_Model_Related_Interface
{
	use DataModels_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels_Model::MODEL_TYPE_RELATED_MTON;


	/**
	 * @param DataModels_Parser_Class $class
	 *
	 * @return DataModels_Model_Interface
	 */
	public static function createByParser( DataModels_Parser_Class $class )
	{
		$model = new static();

		$model->namespace_id = $class->getNamespace()->getId();
		$model->class_name = $class->getClassName();

		foreach( $class->getClassParameters() as $param ) {
			$param_name = $param->getName();

			switch( $param->getName() ) {
				case 'name':
					$model->model_name = $param->getValue();
					break;
				case 'parent_model_class_name':
					$model->parent_model_class_name = $param->getValue();

					$model->internal_parent_model_id = DataModels::getModelInternalId( $param->getValue() );
					break;
				case 'N_model_class_name':
					$model->N_model_class_name = DataModels::getModelInternalId( $param->getValue() );
					break;
				case 'default_order_by':
				case 'database_table_name':
				case 'id_controller_class_name':
				case 'iterator_class_name':
					$model->{$param_name} = $param->getValue();
					break;
				case 'key':
					static::createByParser_keys( $class, $model, $param );
					break;
				case 'relation':
					static::createByParser_relations( $class, $model, $param );
					break;
			}
		}

		static::createByParser_properties( $class, $model );

		return $model;
	}


	/**
	 * @return bool
	 */
	public function canHaveRelated()
	{
		return false;
	}

	/**
	 *
	 */
	public function checkIdProperties() {
		/**
		 * @var DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN $this
		 * @var DataModels_Property[] $_related_properties
		 */

		$_related_properties = [];

		foreach( $this->getProperties() as $property ) {
			if($property->getRelatedToClassName()) {
				$key = $property->getRelatedToClassName().'.'.$property->getRelatedToPropertyName();

				$_related_properties[$key] = $property;
				$this->removeProperty( $property->getInternalId() );
			}
		}


		$main_model = $this->getInternalMainModel();

		foreach( $main_model->getProperties() as $parent_property ) {
			if( !$parent_property->getIsId() ) {
				continue;
			}


			$class = get_class($parent_property);

			$key = 'main:'.$main_model->getInternalId().'.'.$parent_property->getInternalId();

			if(
				!isset($_related_properties[$key]) ||
				get_class($_related_properties[$key])!=$class
			) {
				/**
				 * @var DataModels_Property_Interface $r_id
				 */
				$r_id = new $class();

				if(isset($_related_properties[$key])) {
					$name = $_related_properties[$key]->getName();
				} else {
					$name = $main_model->getModelName().'_'.$parent_property->getName();
				}

				$r_id->setName( $name );
				$r_id->setIsId(false);
				$r_id->setRelatedToClassName( 'main:'.$main_model->getInternalId() );
				$r_id->setRelatedToPropertyName( $parent_property->getInternalId() );
			} else {
				$r_id = $_related_properties[$key];
			}

			$this->addProperty( $r_id );
		}

		$parent_model = $this->getInternalParentModel();
		if(
			$parent_model &&
			$parent_model->getInternalId()!=$main_model->getInternalId()
		) {
			foreach( $parent_model->getProperties() as $parent_property ) {
				if(
					!$parent_property->getIsId() ||
					$parent_property->getRelatedToClassName()
				) {
					continue;
				}

				$class = get_class($parent_property);

				$key = 'parent:'.$parent_model->getInternalId().'.'.$parent_property->getInternalId();

				if(
					!isset($_related_properties[$key]) ||
					get_class($_related_properties[$key])!=$class
				) {
					/**
					 * @var DataModels_Property_Interface $r_id
					 */
					$r_id = new $class();

					if(isset($_related_properties[$key])) {
						$name = $_related_properties[$key]->getName();
					} else {
						$name = $parent_model->getModelName().'_'.$parent_property->getName();
					}

					$r_id->setName( $name );
					$r_id->setIsId(false);
					$r_id->setRelatedToClassName( 'parent:'.$parent_model->getInternalId() );
					$r_id->setRelatedToPropertyName( $parent_property->getInternalId() );
				} else {
					$r_id = $_related_properties[$key];
				}


				$this->addProperty( $r_id );
			}
		}

		$N_model = $this->getNModel();

		if($N_model) {
			foreach( $N_model->getProperties() as $parent_property ) {
				if( !$parent_property->getIsId() ) {
					continue;
				}

				$class = get_class($parent_property);

				$key = 'N:'.$N_model->getInternalId().'.'.$parent_property->getInternalId();

				if(
					!isset($_related_properties[$key]) ||
					get_class($_related_properties[$key])!=$class
				) {
					/**
					 * @var DataModels_Property_Interface $r_id
					 */
					$r_id = new $class();

					if(isset($_related_properties[$key])) {
						$name = $_related_properties[$key]->getName();
					} else {
						$name = $N_model->getModelName().'_'.$parent_property->getName();
					}


					$r_id->setName( $name );
					$r_id->setIsId(false);
					$r_id->setRelatedToClassName( 'N:'.$N_model->getInternalId() );
					$r_id->setRelatedToPropertyName( $parent_property->getInternalId() );
				} else {
					$r_id = $_related_properties[$key];
				}

				$this->addProperty( $r_id );
			}
		}

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
				!$property->getRelatedToClassName() ||
				substr($property->getRelatedToClassName(),0, 2)!='N:'
			) {
				continue;
			}

			$property->setInternalPriority( $i );
			$i++;
		}
		uasort( $this->properties, $sort );


		$i = 300;
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
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name'
			]);
			$model_name_field->setCatcher( function( $value ) {
				$this->setModelName( $value );
			} );



			$class_name_field = new Form_Field_Input('class_name', 'Class name:', $this->class_name);
			$class_name_field->setIsRequired(true);
			$class_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel class name'
			]);
			$class_name_field->setCatcher( function( $value ) {
				$this->setClassName($value);
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

				$this->extends = [];
				foreach( $value as $i=>$v ) {
					$v = trim( $v );
					if($v) {
						$this->extends[] = $v;
					}
				}

			} );



			$database_table_name_field = new Form_Field_Input('database_table_name', 'Custom table name:', $this->database_table_name);
			$database_table_name_field->setCatcher( function( $value ) {
				$this->setDatabaseTableName( $value );
			} );

			$n_model_field = new Form_Field_Select('N_model_class_name', 'N DataModel:', $this->N_model_class_name );
			$n_model_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select N DataModel'
			]);

			$n_classes = [
				'' => ''
			];
			foreach( DataModels::getModels() as $model ) {
				if(
					!$model instanceof DataModels_Model ||
					$model->getInternalId()==$this->getRelevantParentModel()->getInternalId()
				) {
					continue;
				}

				$n_classes[$model->getInternalId()] = $model->getModelName().' ('.$model->getClassName().')';
			}
			$n_model_field->setSelectOptions( $n_classes );
			$n_model_field->setCatcher( function( $value ) {
				$this->setNModel( $value );
			} );

			$default_order_by_field = new Form_Field_Hidden( 'default_order_by', '', implode('|', $this->getDefaultOrderBy()) );
			$default_order_by_field->setCatcher( function( $value ) {
				if(!$value) {
					$value = [];
				} else {
					$value = explode('|', $value);
				}
				$this->setDefaultOrderBy( $value );
			} );


			$fields = [
				$is_abstract_filed,
				$model_name_field,
				$class_name_field,
				$extends_field,
				$implements_field,
				$database_table_name_field,
				$n_model_field,
				$default_order_by_field
			];

			$this->__edit_form = new Form('edit_model_form', $fields );
			$this->__edit_form->setAction( DataModels::getActionUrl('edit') );

		}

		return $this->__edit_form;
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
		$this->checkIdProperties();

		DataModels::check();

		return true;
	}

	/**
	 * @return string
	 */
	public function getNModelClassName()
	{
		return $this->N_model_class_name;
	}

	/**
	 * @param string $N_model_class_name
	 */
	public function setNModelClassName( $N_model_class_name )
	{
		$this->N_model_class_name = $N_model_class_name;
	}

	/**
	 *
	 * @param string $n_model_id
	 */
	public function setNModel( $n_model_id )
	{
		$this->N_model_class_name = $n_model_id;
		$this->actualizeRelationProperties();
	}

	/**
	 * @return null|DataModels_Model_Related_Interface
	 */
	public function getNModel()
	{
		if(!$this->N_model_class_name) {
			return null;
		}

		return DataModels::getModel($this->N_model_class_name);
	}

	/**
	 *
	 */
	public function actualizeRelationProperties()
	{
		$this->checkIdProperties();
	}

	/**
	 *
	 */
	public function showEditFormFields()
	{
		$form = $this->getEditForm();

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
		echo $form->field('N_model_class_name');

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

	/**
	 * @return array
	 */
	public function getOrderByOptions()
	{
		$res = [];

		foreach( $this->getProperties() as $property ) {
			if(
				$property->getRelatedToClassName() ||
				$property->getType()==DataModel::TYPE_CUSTOM_DATA
			) {
				continue;
			}

			$res[$this->getInternalId().'.'.$property->getInternalId()] = $this->getModelName().'.'.$property->getName();
		}

		$n = $this->getNModel();
		if($n) {
			foreach( $n->getProperties() as $property ) {
				if(
					$property->getDataModelClassName() ||
					$property->getType()==DataModel::TYPE_CUSTOM_DATA
				) {
					continue;
				}

				$res[$n->getInternalId().'.'.$property->getInternalId()] = $n->getModelName().'.'.$property->getName();
			}
		}


		return $res;
	}


	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{
		/**
		 * @var DataModels_Model_Related_MtoN $model
		 */
		$model = $this;

		$class = new ClassCreator_Class();
		$project_namespace = Project::getNamespace( $model->getNamespaceId() );

		$class->setNamespace( $project_namespace->getNamespace() );
		$class->setName( $model->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_MtoN') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_MtoN') );

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
		 * @var DataModels_Model_Related_MtoN $model
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

		$parent_id = $model->getInternalParentModelId();
		if(!$parent_id) {
			$parent_id = $model->getInternalMainModelId();
		}

		$parent_class = DataModels::getModel( $parent_id );
		if(!$parent_class) {
			$class->addError( Tr::_('Fatal: unknown parent class!') );

			return;
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'parent_model_class_name', var_export($parent_class->getClassName(), true) ))
		);


		$N_model_id = $this->getNModelClassName();
		$N_model = DataModels::getModel( $N_model_id );
		if(!$N_model) {
			$class->addError('Unable to get N DataModel definition (N model ID: '.$N_model_id.')');
			return;
		}

		$N_model_class_name = $N_model->getClassName();

		if($N_model->getNamespaceId()!=Project::getCurrentNamespaceId()) {

			$ns = Project::getNamespace($N_model->getNamespaceId());

			$class->addUse(
				new ClassCreator_UseClass($ns->getNamespace(), $N_model_class_name)
			);
		}


		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'N_model_class_name', var_export($N_model_class_name, true)) )
		);


		$order_by = [];
		foreach( $model->getDefaultOrderBy() as $ob ) {
			$direction = $ob[0];
			$ob = substr( $ob, 1 );

			[ $s_model_id, $s_property_id ] = explode('.', $ob);

			$s_model = DataModels::getModel( $s_model_id );
			$s_property = $s_model->getProperty( $s_property_id );


			if($s_model->getModelName()!=$model->getModelName()) {
				$order_by[] = var_export($direction.$s_model->getModelName().'.'.$s_property->getName(), true);
			}
		}

		if($order_by) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'default_order_by', $order_by))
			);
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_ID( ClassCreator_Class $class )
	{
	}

	/**
	 * @param string $related_to
	 *
	 * @return array
	 */
	public function parseRelatedTo( $related_to )
	{
		[$what, $property_name] = explode('.', $related_to);

		$related_to_model = null;
		$related_to_property = null;


		if($what==DataModels::getModel($this->getNModelClassName())->getModelName()) {
			$related_to_model = DataModels::getModel( $this->getNModelClassName() );
		} else {
			if($what=='main') {
				$related_to_model = DataModels::getModel( $this->getInternalMainModelId() );
			} else {
				$related_to_model = DataModels::getModel( $this->getInternalParentModelId() );
			}
		}

		if($related_to_model) {
			foreach( $related_to_model->getProperties() as $property ) {
				if($property_name==$property->getName()) {
					$related_to_property = $property;
					break;
				}
			}
		}

		return [$related_to_model, $related_to_property];
	}


}