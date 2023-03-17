<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model_Related_1toN as Jet_DataModel_Definition_Model_Related_1toN;
use Jet\Form;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_1toN extends Jet_DataModel_Definition_Model_Related_1toN implements DataModel_Definition_Model_Related_Interface
{

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected string $internal_type = DataModel::MODEL_TYPE_RELATED_1TON;


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
			static::$create_form = static::getCreateForm_Related( DataModel::MODEL_TYPE_RELATED_1TON );
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Related_1toN
	 */
	public static function catchCreateForm(): bool|DataModel_Definition_Model_Related_1toN
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$class = static::catchCreateForm_createClass( $form );

		$model = new DataModel_Definition_Model_Related_1toN();
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
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_'.DataModel::MODEL_TYPE_RELATED_1TON ) );

		$class->setExtends( $this->createClass_getExtends( $class, 'DataModel_'.DataModel::MODEL_TYPE_RELATED_1TON ) );

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


		$parent_class = $this->getParentModelClassName();
		if( !$parent_class ) {
			$parent_class = $this->getMainModelClassName();
		}


		$parent_class = DataModels::getClass( $parent_class );
		if( !$parent_class ) {
			$class->addError( Tr::_( 'Fatal: unknown parent class!' ) );

			return;
		}

		$class->setAttribute( 'DataModel_Definition', 'parent_model_class', $parent_class->getClassName() . '::class' );

		if( $this->getDefaultOrderBy() ) {
			$class->setAttribute( 'DataModel_Definition', 'default_order_by', $this->getDefaultOrderBy() );
		}

	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy( array $default_order_by ): void
	{
		$this->default_order_by = $default_order_by;
	}


	/**
	 * @return array
	 */
	public function getOrderByOptions(): array
	{
		$res = [];

		foreach( $this->getProperties() as $property ) {
			if(
				$property->getRelatedToClassName() ||
				$property->getType() == DataModel::TYPE_CUSTOM_DATA
			) {
				continue;
			}

			$res[$property->getName()] = $property->getName();
		}


		return $res;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_methods( ClassCreator_Class $class ): void
	{
		$id_property_name = '';
		foreach( $this->getProperties() as $property ) {
			if(
				$property->getIsId() &&
				!$property->getRelatedToClassName()
			) {
				$id_property_name = $property->getName();
				break;
			}
		}

		$get_array_key_value = $class->createMethod( 'getArrayKeyValue' );
		$get_array_key_value->setReturnType('string');

		if($id_property_name) {
			$get_array_key_value->line( 1, 'return $this->'.$id_property_name.';' );
		} else {
			$get_array_key_value->line( 1, '//TODO: implement ...' );
			$get_array_key_value->line( 1, 'return \'\';' );
		}


		foreach( $this->getProperties() as $property ) {
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

}