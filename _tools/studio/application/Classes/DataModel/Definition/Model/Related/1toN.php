<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model_Related_1toN as Jet_DataModel_Definition_Model_Related_1toN;
use Jet\Form;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_1toN extends Jet_DataModel_Definition_Model_Related_1toN implements DataModel_Definition_Model_Related_Interface {

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected string $internal_type = DataModels::MODEL_TYPE_RELATED_1TON;


	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @return Form
	 */
	public static function getCreateForm() : Form
	{
		if(!static::$create_form) {
			static::$create_form = DataModel_Definition_Model_Trait::getCreateForm_Related('1toN');
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Related_1toN
	 */
	public static function catchCreateForm() : bool|DataModel_Definition_Model_Related_1toN
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$class = DataModel_Definition_Model_Trait::catchCreateForm_createClass($form);

		$model = new DataModel_Definition_Model_Related_1toN();
		$model->setClass( $class );

		DataModel_Definition_Model_Trait::catchCreateForm_modelMainSetup( $form, $model );
		DataModel_Definition_Model_Trait::catchCreateForm_relatedModelSetup( $form, $model );


		return $model;
	}

	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass() : ClassCreator_Class
	{
		$class = new ClassCreator_Class();

		$class->setNamespace( $this->_class->getNamespace() );
		$class->setName( $this->_class->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Definition') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1toN') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1toN') );

		return $class;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class ) : void
	{
		$class->setAttribute( 'DataModel_Definition', 'name', $this->getModelName() );

		if($this->getDatabaseTableName()) {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getDatabaseTableName() );
		} else {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getModelName() );
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

		$class->setAttribute( 'DataModel_Definition', 'parent_model_class', $parent_class->getClassName().'::class' );

		$iterator_class = $this->getIteratorClassName();


		if($iterator_class!='Jet\\DataModel_Related_1toN_Iterator') {

			if(substr( $iterator_class, 0, 4 )=='Jet\\') {
				$iterator_class = substr( $iterator_class, 4 );

				$class->addUse( new ClassCreator_UseClass('Jet', $iterator_class) );
			}

			$class->setAttribute( 'DataModel_Definition', 'iterator_class', $iterator_class.'::class' );
		}

		if($this->getDefaultOrderBy()) {
			$class->setAttribute( 'DataModel_Definition', 'default_order_by', $this->getDefaultOrderBy() );
		}

	}



	/**
	 * @param string $iterator_class
	 */
	public function setIteratorClass( string $iterator_class) : void
	{
		$this->iterator_class = $iterator_class;
	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy( array $default_order_by ) : void
	{
		$this->default_order_by = $default_order_by;
	}


	/**
	 * @return array
	 */
	public function getOrderByOptions() : array
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




}