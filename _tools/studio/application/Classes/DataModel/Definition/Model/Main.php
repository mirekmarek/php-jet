<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use Jet\Form;
use Jet\DataModel;
use Jet\DataModel_IDController_AutoIncrement;

/**
 */
class DataModel_Definition_Model_Main extends Jet_DataModel_Definition_Model_Main implements DataModel_Definition_Model_Interface
{

	use DataModel_Definition_Model_Trait;


	/**
	 * @var string
	 */
	protected string $internal_type = DataModel::MODEL_TYPE_MAIN;

	/**
	 *
	 * @var string
	 */
	protected string $id_controller_class = DataModel_IDController_AutoIncrement::class;


	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;


	/**
	 * @return Form
	 */
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {
			static::$create_form = static::getCreateForm_Main();
		}

		return static::$create_form;
	}

	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass(): ClassCreator_Class
	{

		$class = new ClassCreator_Class();

		$class->setNamespace( $this->_class->getNamespace() );
		$class->setName( $this->_class->getClassName() );

		$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel' ) );
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_Definition' ) );

		$class->setExtends( $this->createClass_getExtends( $class, 'DataModel' ) );

		return $class;
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_methods( ClassCreator_Class $class ): void
	{
		$model = $this;

		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Form' ) );
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Form_Field' ) );


		$_form_edit = new ClassCreator_Class_Property( '_form_edit', 'Form', 'Form' );
		$_form_edit->setDefaultValue( null );
		$class->addProperty( $_form_edit );

		$_form_add = new ClassCreator_Class_Property( '_form_add', 'Form', 'Form' );
		$_form_add->setDefaultValue( null );
		$class->addProperty( $_form_add );

		$getEditForm = $class->createMethod( 'getEditForm' );
		$getEditForm->setReturnType( 'Form' );
		$getEditForm->line( 1, 'if(!$this->_form_edit) {' );
		$getEditForm->line( 2, '$this->_form_edit = $this->createForm(\'edit_form\');' );
		$getEditForm->line( 1, '}' );
		$getEditForm->line( 1, '' );
		$getEditForm->line( 1, 'return $this->_form_edit;' );

		$catchEditForm = $class->createMethod( 'catchEditForm' );
		$catchEditForm->setReturnType( 'bool' );
		$catchEditForm->line( 1, 'return $this->getEditForm()->catch();' );


		$getAddForm = $class->createMethod( 'getAddForm' );
		$getAddForm->setReturnType( 'Form' );
		$getAddForm->line( 1, 'if(!$this->_form_add) {' );
		$getAddForm->line( 2, '$this->_form_add = $this->createForm(\'add_form\');' );
		$getAddForm->line( 1, '}' );
		$getAddForm->line( 1, '' );
		$getAddForm->line( 1, 'return $this->_form_add;' );

		$catchAddForm = $class->createMethod( 'catchAddForm' );
		$catchAddForm->setReturnType( 'bool' );
		$catchAddForm->line( 1, 'return $this->getAddForm()->catch();' );


		$get = $class->createMethod( 'get' );
		$get->setIsStatic( true );
		$get->addParameter( 'id' )->setType( 'int|string' );
		$get->setReturnType( 'static|null' );
		$get->line( 1, 'return static::load( $id );' );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Fetch_Instances') );
		
		$getList = $class->createMethod( 'getList' );
		$getList->setIsStatic( true );
		$getList->setReturnType( 'iterable' );
		$getList->setReturnTypeNoInspection( true );
		$getList->setReturnTypeForDoc( 'static[]|DataModel_Fetch_Instances' );
		$getList->line( 1, '$where = [];' );
		$getList->line( 1, '' );
		$getList->line( 1, 'return static::fetchInstances( $where );' );

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			$property->createClassMethods( $class );
		}

		if( ($id_controller_definition = $this->getIDControllerDefinition()) ) {
			$id_controller_definition->createClassMethods( $class );
		}

	}


	/**
	 * @return bool|DataModel_Definition_Model_Main
	 */
	public static function catchCreateForm(): bool|DataModel_Definition_Model_Main
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$class = static::catchCreateForm_createClass( $form );

		$model = new DataModel_Definition_Model_Main();
		$model->setClass( $class );
		
		static::catchCreateForm_modelMainSetup( $form, $model );

		return $model;
	}

}