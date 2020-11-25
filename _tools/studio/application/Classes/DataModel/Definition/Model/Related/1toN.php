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
use Jet\Form;
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
			static::$create_form = DataModel_Definition_Model_Trait::getCreateForm_Related('1toN');
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Related_1toN
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

		$model = new DataModel_Definition_Model_Related_1toN();
		$model->setClass( $class );

		DataModel_Definition_Model_Trait::catchCreateForm_modelMainSetup( $form, $model );
		DataModel_Definition_Model_Trait::catchCreateForm_relatedModelSetup( $form, $model );


		return $model;
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




}