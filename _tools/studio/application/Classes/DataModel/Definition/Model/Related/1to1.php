<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Model_Related_1to1 as Jet_DataModel_Definition_Model_Related_1to1;
use Jet\Form;
use Jet\Tr;
use Jet\DataModel;

/**
 */
class DataModel_Definition_Model_Related_1to1 extends Jet_DataModel_Definition_Model_Related_1to1 implements DataModel_Definition_Model_Related_Interface
{

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected string $internal_type = DataModel::MODEL_TYPE_RELATED_1TO1;

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
			static::$create_form = static::getCreateForm_Related( DataModel::MODEL_TYPE_RELATED_1TO1 );
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Related_1to1
	 */
	public static function catchCreateForm(): bool|DataModel_Definition_Model_Related_1to1
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$class = static::catchCreateForm_createClass( $form );

		$model = new DataModel_Definition_Model_Related_1to1();
		$model->setClass( $class );

		static::catchCreateForm_modelMainSetup( $form, $model );
		static::catchCreateForm_relatedModelSetup( $form, $model );
		
		$relation_property_name = $form->field('relation_property_name')->getValue();
		
		if( !$model->create($relation_property_name) ) {
			return false;
		}
		
		return $model;
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
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_'.DataModel::MODEL_TYPE_RELATED_1TO1 ) );

		$class->setExtends( $this->createClass_getExtends( $class, 'DataModel_'.DataModel::MODEL_TYPE_RELATED_1TO1 ) );

		return $class;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class ): void
	{

		$class->setAttribute( 'DataModel_Definition', 'name', $this->getModelName() );

		if( $this->getDatabaseTableName() ) {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getDatabaseTableName() );
		} else {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getModelName() );
		}


		$parent_class = $this->parent_model_class;
		if( !$parent_class ) {
			$parent_class = $this->main_model_class_name;
		}

		$parent_class = DataModels::getClass( $parent_class );
		if( !$parent_class ) {
			$class->addError( Tr::_( 'Fatal: unknown parent class!' ) );

			return;
		}

		$class->setAttribute( 'DataModel_Definition', 'parent_model_class', $parent_class->getClassName() . '::class' );

	}

}