<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Related_1to1 as Jet_DataModel_Definition_Model_Related_1to1;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_1to1 extends Jet_DataModel_Definition_Model_Related_1to1 implements DataModel_Definition_Model_Related_Interface{

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels::MODEL_TYPE_RELATED_1TO1;

	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields( '1to1' );

			$current_class = DataModels::getCurrentClass();
			$current_model = DataModels::getCurrentModel();

			if( $current_class ) {
				$fields['model_name']->setDefaultValue( $current_model->getModelName().'_' );
				$fields['class_name']->setDefaultValue( $current_class->getClassName().'_' );
			}

			static::$create_form = new Form('create_data_model_form', $fields );


			static::$create_form->setAction( DataModels::getActionUrl('model/add') );

		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Interface
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}


		$namespace = $form->field('namespace')->getValue();
		$class_name = $form->field('class_name')->getValue();
		$script_path = $form->field('script_path')->getValue();
		$model_name = $form->field('model_name')->getValue();
		$id_controller_class = $form->field('id_controller_class')->getValue();
		$id_property_name = $form->field('id_property_name')->getValue();

		$class = new DataModel_Class(
			$script_path,
			$namespace,
			$class_name
		);

		$class->setIsNew( true );

		$model = new DataModel_Definition_Model_Related_1to1();
		$model->setClass( $class );

		$model->setModelName( $model_name );
		$model->setIDControllerClassName(  $id_controller_class);


		switch($id_controller_class) {
			case 'Jet\DataModel_IDController_AutoIncrement':
				$id_property = new DataModel_Definition_Property_IdAutoIncrement( $class->getFullClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_UniqueString':
			case 'Jet\DataModel_IDController_Name':
				$id_property = new DataModel_Definition_Property_Id( $class->getFullClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_Passive':
				$id_property = new DataModel_Definition_Property_Id( $class->getFullClassName(), $id_property_name);
				$id_controller_option = '';
				break;
			default:
				throw new DataModel_Exception('Unknown ID controller class '.$id_controller_class);
		}

		$id_property->setIsId(true);
		$model->addProperty($id_property);

		if($id_controller_option) {
			$model->getIDController()->setOptions([
				$id_controller_option => $id_property_name
			]);
		}

		//TODO:

		return $model;
	}



	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{

		$class = new ClassCreator_Class();

		$class->setName( $this->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1to1') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1to1') );

		return $class;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class )
	{

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'name', var_export($this->getModelName(), true)) )
		);

		if($this->getDatabaseTableName()) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getDatabaseTableName(), true)) )
			);
		} else {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getModelName(), true)) )
			);
		}


		$parent_class = $this->parent_model_class_name;
		if(!$parent_class) {
			$parent_class = $this->main_model_class_name;
		}

		$parent_class = DataModels::getClass( $parent_class );
		if(!$parent_class) {
			$class->addError( Tr::_('Fatal: unknown parent class!') );

			return;
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'parent_model_class_name', var_export($parent_class->getClassName(), true) ))
		);

	}

}