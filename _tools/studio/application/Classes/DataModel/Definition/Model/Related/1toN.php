<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model_Related_1toN as Jet_DataModel_Definition_Model_Related_1toN;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_1toN extends Jet_DataModel_Definition_Model_Related_1toN implements DataModel_Definition_Model_Related_Interface {

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels::MODEL_TYPE_RELATED_1TON;


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
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields( '1toN' );

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

		$model = new DataModel_Definition_Model_Related_1toN();
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
	 * @param string $iterator_class_name
	 */
	public function setIteratorClassName($iterator_class_name)
	{
		$this->iterator_class_name = $iterator_class_name;
	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy($default_order_by)
	{
		$this->default_order_by = $default_order_by;
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

			$res[$property->getName()] = $property->getName();
		}


		return $res;
	}



	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{
		$class = new ClassCreator_Class();

		$class->setName( $this->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1toN') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1toN') );

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


		$parent_class = $this->getParentModelClassName();
		if(!$parent_class) {
			$parent_class = $this->getMainModelClassName();
		}


		$parent_class = DataModels::getClass( $parent_class );
		if(!$parent_class) {
			$class->addError( Tr::_('Fatal: unknown parent class!') );

			return;
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'parent_model_class_name', var_export($parent_class->getClassName(), true) ))
		);

		$iterator_class_name = $this->getIteratorClassName();


		if($iterator_class_name!='Jet\\DataModel_Related_1toN_Iterator') {

			if(substr( $iterator_class_name, 0, 4 )=='Jet\\') {
				$iterator_class_name = substr( $iterator_class_name, 4 );

				$class->addUse( new ClassCreator_UseClass('Jet', $iterator_class_name) );
			}

			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'iterator_class_name', var_export($iterator_class_name, true) ))
			);
		}


		$order_by = [];
		foreach( $this->getDefaultOrderBy() as $ob ) {
			$order_by[] = var_export($ob, true);
		}

		if($order_by) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'default_order_by', $order_by))
			);
		}

	}

}