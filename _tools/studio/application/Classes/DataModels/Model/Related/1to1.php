<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model_Related_1to1;
use Jet\Tr;

class DataModels_Model_Related_1to1 extends DataModel_Definition_Model_Related_1to1 implements DataModels_Model_Related_Interface
{
	use DataModels_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels_Model::MODEL_TYPE_RELATED_1TO1;

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
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{
		/**
		 * @var DataModels_Model_Related_1to1 $model
		 */
		$model = $this;

		$class = new ClassCreator_Class();
		$project_namespace = Project::getNamespace( $model->getNamespaceId() );

		$class->setNamespace( $project_namespace->getNamespace() );
		$class->setName( $model->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1to1') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1to1') );

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
		 * @var DataModels_Model_Related_1to1 $model
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

	}

}