<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Related_1toN;
use Jet\DataModel;
use Jet\DataModel_Related_1toN;
use Jet\Tr;

//TODO: pridat getArrayKeyValue
class DataModels_Model_Related_1toN extends DataModel_Definition_Model_Related_1toN implements DataModels_Model_Related_Interface
{
	use DataModels_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels_Model::MODEL_TYPE_RELATED_1TON;

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

			$res[$this->getInternalId().'.'.$property->getInternalId()] = $this->getModelName().'.'.$property->getName();
		}


		return $res;
	}



	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{
		/**
		 * @var DataModels_Model_Related_1toN $model
		 */
		$model = $this;

		$class = new ClassCreator_Class();
		$project_namespace = Project::getNamespace( $model->getNamespaceId() );

		$class->setNamespace( $project_namespace->getNamespace() );
		$class->setName( $model->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1toN') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1toN') );

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
		 * @var DataModels_Model_Related_1toN $model
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

		$iterator_class_name = $model->getIteratorClassName();

		if(substr( $iterator_class_name, 0, 4 )=='Jet\\') {
			$iterator_class_name = substr( $iterator_class_name, 4 );

			$class->addUse( new ClassCreator_UseClass('Jet', $iterator_class_name) );
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'iterator_class_name', var_export($iterator_class_name, true) ))
		);


		$order_by = [];
		foreach( $model->getDefaultOrderBy() as $ob ) {
			$direction = $ob[0];
			$ob = substr( $ob, 1 );

			list( $s_model_id, $s_property_id ) = explode('.', $ob);

			$s_model = DataModels::getModel( $s_model_id );
			$s_property = $s_model->getProperty( $s_property_id );


			$order_by[] = var_export($direction.$s_property->getName(), true);
		}

		if($order_by) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'default_order_by', $order_by))
			);
		}

	}
}