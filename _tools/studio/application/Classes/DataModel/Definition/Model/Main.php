<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Input;

/**
 */
class DataModel_Definition_Model_Main extends Jet_DataModel_Definition_Model_Main implements DataModel_Definition_Model_Interface {

	use DataModel_Definition_Model_Trait;


	/**
	 * @var string
	 */
	protected string $internal_type = DataModels::MODEL_TYPE_MAIN;

	/**
	 *
	 * @var string
	 */
	protected string $id_controller_class = 'Jet\DataModel_IDController_AutoIncrement';



	/**
	 * @var Form
	 */
	protected static Form $create_form;




	/**
	 * @return Form
	 */
	public static function getCreateForm() : Form
	{
		if(!static::$create_form) {
			static::$create_form = DataModel_Definition_Model_Trait::getCreateForm_Main();
		}

		return static::$create_form;
	}

	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{

		$class = new ClassCreator_Class();

		$class->setNamespace( $this->_class->getNamespace() );
		$class->setName( $this->_class->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->setExtends( $this->createClass_getExtends($class, 'DataModel') );

		return $class;
	}


	/**
	 * @return bool|DataModel_Definition_Model_Main
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

		$class = DataModel_Definition_Model_Trait::catchCreateForm_createClass($form);

		$model = new DataModel_Definition_Model_Main();
		$model->setClass( $class );

		DataModel_Definition_Model_Trait::catchCreateForm_modelMainSetup( $form, $model );

		return $model;
	}

}