<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Data_Tree;
use Jet\DataModel;
use Jet\DataModel_Definition_Relation_Join_Item;
use Jet\Http_Request;
use Jet\Form;
use Jet\Exception;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\SysConf_URI;

//TODO: v aktualizacnim SQL chybi strednik za jednim SQL prikazem

class DataModels extends BaseObject implements Application_Part
{

	/**
	 * @var null|DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	protected static $__current_model;

	/**
	 * @var Project_Namespace|bool
	 */
	protected static $__current_namespace;


	/**
	 * @var DataModels_Model[]|DataModels_Model_Related_1to1[]|DataModels_Model_Related_1toN[]|DataModels_Model_Related_MtoN[]
	 */
	protected static $models;




	/**
	 * @return DataModels_Model[]|DataModels_Model_Related_1to1[]|DataModels_Model_Related_1toN[]|DataModels_Model_Related_MtoN[]
	 */
	public static function load()
	{
		if(static::$models===null) {
			static::$models = [];

			$models = Project::readProjectEntity( 'data_models' );
			if(!is_array($models)) {
				$models = [];
			}

			static::$models = $models;
		}

		return static::$models;
	}

	/**
	 * @param Form $form
	 *
	 * @return bool
	 */
	public static function save( Form $form=null )
	{
		static::load();

		$ok = true;
		try {
			foreach( static::$models as $id=>$model ) {
				Project::writeProjectEntity('data_models', $id, $model );
			}

		} catch( Exception $e ) {
			$ok = false;

			Application::handleError( $e, $form );
		}

		return $ok;
	}


	/**
	 * @return DataModels_Model[]|DataModels_Model_Related_1to1[]|DataModels_Model_Related_1toN[]|DataModels_Model_Related_MtoN[]
	 */
	public static function getModels()
	{
		static::load();

		return static::$models;
	}


	/**
	 * @param $action
	 * @param array $custom_get_params
	 * @param string $custom_model_id
	 * @param string $custom_namespace_id]
	 *
	 * @return string $url
	 */
	public static function getActionUrl( $action, array $custom_get_params=[], $custom_model_id=null, $custom_namespace_id=null )
	{

		$get_params = [];


		if(Project::getCurrentNamespaceId()) {
			$get_params['namespace'] = Project::getCurrentNamespaceId();
		}

		if($custom_namespace_id!==null) {
			$get_params['namespace'] = $custom_namespace_id;
			if(!$custom_namespace_id) {
				unset( $get_params['namespace'] );
			}
		}

		if(static::getCurrentModelId()) {
			$get_params['model'] = static::getCurrentModelId();
		}

		if($custom_model_id!==null) {
			$get_params['model'] = $custom_model_id;
			if(!$custom_model_id) {
				unset( $get_params['model'] );
			}
		}

		if($action) {
			$get_params['action'] = $action;
		}

		if($custom_get_params) {
			foreach( $custom_get_params as $k=>$v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::BASE().'data_model.php?'.http_build_query($get_params);
	}


	/**
	 * @return Data_Tree
	 */
	public static function getModelsTree()
	{
		$tree_data = [];


		$getChildren = function(DataModels_Model_Interface $model ) use (&$tree_data, &$getChildren) {
			$parent_id = $model->getNamespaceId();

			if( $model instanceof DataModels_Model_Related_Interface) {
				$parent_id = $model->getInternalParentModelId();
				if(!$parent_id) {
					$parent_id = $model->getInternalMainModelId();
				}
			}

			$tree_data[] = [
				'id' => $model->getInternalId(),
				'parent_id' => $parent_id,
				'label' => $model->getClassName().' ('.$model->getModelName().')',
				'type' => $model->getInternalType(),
				'namespace' => $model->getNamespaceId()
			];

			foreach( $model->getChildren() as $ch ) {
				if($ch) {
					$getChildren( $ch );
				}
			}
		};

		foreach( Project::getNamespaces() as $ns ) {
			if($ns->isInternal()) {
				continue;
			}

			/**
			 * @var Project_Namespace $ns
			 */
			$tree_data[] = [
				'id' => $ns->getId(),
				'parent_id' => '',
				'label' => $ns->getLabel(),
				'type' => 'namespace'
			];

		}

		foreach( static::getModels() as $model ) {
			if( !$model instanceof DataModels_Model) {
				continue;
			}

			$getChildren( $model );
		}


		$tree = new Data_Tree();
		$tree->setIdKey('id');
		$tree->setParentIdKey('parent_id');
		$tree->setLabelKey('label');

		$tree->setData( $tree_data );

		return $tree;
	}


	/**
	 * @param string $id
	 *
	 * @return null|DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	public static function getModel( $id )
	{
		static::load();

		if(!isset(static::$models[$id])) {
			return null;
		}

		return static::$models[$id];
	}

	/**
	 * @param string $full_class_name
	 * @return null|string
	 */
	public static function getModelInternalId( $full_class_name )
	{
		foreach( static::getModels() as $model ) {
			if($model->getFullClassName()==$full_class_name) {
				return $model->getInternalId();
			}
		}

		return null;
	}

	/**
	 * @param DataModels_Model_Interface $model
	 */
	public static function addModel(DataModels_Model_Interface $model )
	{
		static::load();

		static::$models[$model->getInternalId()] = $model;
	}


	/**
	 * @param $model_id
	 *
	 * @return bool
	 */
	public static function deleteModel( $model_id )
	{
		static::load();

		unset( static::$models[$model_id] );

		Project::deleteProjectEntity('data_models', $model_id );

		static::check();

		return true;
		
	}
	
	
	/**
	 * @return string|bool
	 */
	public static function getCurrentModelId()
	{
		if(static::getCurrentModel()) {
			return static::getCurrentModel()->getInternalId();
		}

		return false;
	}

	/**
	 * @return null|DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	public static function getCurrentModel()
	{
		if(static::$__current_model===null) {
			$id = Http_Request::GET()->getString('model');

			static::$__current_model = false;

			if(
				$id &&
				($model=static::getModel($id))
			) {
				static::$__current_model = $model;
			}
		}

		return static::$__current_model;
	}

	/**
	 * @param string $model_name
	 * @param string $class_name
	 *
	 * @return DataModels_Model
	 */
	public static function createModel( $model_name, $class_name )
	{
		$model = new DataModels_Model();
		$model->setNamespaceId( Project::getCurrentNamespaceId() );
		$model->setModelName( $model_name );
		$model->setClassName( $class_name );
		$model->checkIdProperties();

		static::addModel( $model );

		return $model;
	}

	/**
	 * @param string $model_name
	 * @param string $class_name
	 * @param DataModels_Model_Interface $parent
	 *
	 * @return DataModels_Model_Related_1to1
	 */
	public static function createModel_Related_1to1($model_name, $class_name, DataModels_Model_Interface $parent )
	{
		$model = new DataModels_Model_Related_1to1();
		$model->setNamespaceId( Project::getCurrentNamespaceId() );
		$model->setModelName( $model_name );
		$model->setClassName( $class_name );
		$model->setInternalParentModel( $parent );
		$model->checkIdProperties();

		static::addModel( $model );

		return $model;
	}

	/**
	 * @param string $model_name
	 * @param string $class_name
	 * @param DataModels_Model_Interface $parent
	 *
	 * @return DataModels_Model_Related_1toN
	 */
	public static function createModel_Related_1toN($model_name, $class_name, DataModels_Model_Interface $parent )
	{
		$model = new DataModels_Model_Related_1toN();
		$model->setNamespaceId( Project::getCurrentNamespaceId() );
		$model->setModelName( $model_name );
		$model->setClassName( $class_name );
		$model->setInternalParentModel( $parent );
		$model->checkIdProperties();

		static::addModel( $model );

		return $model;
	}

	/**
	 * @param string $model_name
	 * @param string $class_name
	 * @param DataModels_Model_Interface $parent
	 *
	 * @return DataModels_Model_Related_MtoN
	 */
	public static function createModel_Related_MtoN($model_name, $class_name, DataModels_Model_Interface $parent )
	{
		$model = new DataModels_Model_Related_MtoN();
		$model->setNamespaceId( Project::getCurrentNamespaceId() );
		$model->setModelName( $model_name );
		$model->setClassName( $class_name );
		$model->setInternalParentModel( $parent );
		$model->checkIdProperties();

		static::addModel( $model );

		return $model;
	}

	/**
	 *
	 */
	public static function check()
	{
		foreach( static::getModels() as $model ) {
			$model->checkIdProperties();
			$model->checkOuterRelations();

			//TODO: kontrola indexu
			//TODO: kontrola DataModel vlastnposti nenavazanych / navazanych
			$model->checkSortOfProperties();
		}

	}

	/**
	 *
	 * @return Project_Namespace[]
	 */
	public static function getNamespaces()
	{
		return [];
	}

	/**
	 *
	 */
	public static function synchronize()
	{
		static::load();

		$parser = new DataModels_Parser();
		$parser->parse();


		/**
		 * @var DataModels_Parser_Class[] $classes
		 */
		$classes = [];
		$imported_classes = [];
		$imported_models = [];
		$updated = false;

		foreach( $parser->getClasses() as $class ) {
			if(
				$parser->getClassNamespace( $class->getFullClassName() )->isInternal()
			) {
				continue;
			}


			$classes[$class->getFullClassName()] = $class;
		}

		$import = function( DataModels_Parser_Class $class ) use ( &$classes, &$imported_classes, &$updated, &$imported_models, &$parser ) {
			$class_name = $class->getFullClassName();

			$model = null;


			$exists = false;
			foreach( static::getModels() as $e_m ) {
				if( $e_m->getFullClassName()==$class->getFullClassName() ) {
					$exists = true;
					break;
				}
			}

			if($exists) {
				$imported_classes[] = $class_name;
			} else {
				switch($class->getBaseClass()) {
					case 'Jet\DataModel':
						$model = DataModels_Model::createByParser( $class );
						break;
					case 'Jet\DataModel_Related_1toN':
						$model = DataModels_Model_Related_1toN::createByParser( $class );
						break;
					case 'Jet\DataModel_Related_1to1':
						$model = DataModels_Model_Related_1to1::createByParser( $class );
						break;
					case 'Jet\DataModel_Related_MtoN':
						$model = DataModels_Model_Related_MtoN::createByParser( $class );
						break;
				}

				if($class->getExtends()) {
					$model->setExtends( $class->getExtends(), false );
				}

				if($class->getImplements()) {
					$model->setImplements( $class->getImplements() );
				}

				$model->setIsAbstract( $class->isAbstract() );

				if($model) {
					$updated = true;
					DataModels::addModel( $model );

					$imported_models[] = $model;

					$imported_classes[] = $class_name;
				}
			}


			unset($classes[$class_name]);
		};


		foreach( $classes as $class_name=>$class ) {
			if(
				!$class->isMainDataModel()
				||
				(
					$class->getExtends() &&
					!$parser->getClassNamespace( $class->getExtends() )->isInternal()
				)
			) {
				continue;
			}

			$import( $class );
		}


		foreach( $classes as $class_name=>$class ) {
			if( !$class->isMainDataModel() ) {
				continue;
			}


			$import( $class );
		}


		do {
			$something_imported = false;
			foreach( $classes as $class_name=>$class ) {

				if(!in_array($class->getParentClass(), $imported_classes)) {
					continue;
				}

				$import( $class );

				$something_imported = true;
			}

			if(!$classes) {
				break;
			}
		} while( $something_imported );



		/**
		 * @var DataModels_Model_Interface[] $imported_models
		 */
		foreach( $imported_models as $model ) {
			if($model->getExtends()) {
				$internal_id = DataModels::getModelInternalId( $model->getExtends() );
				if($internal_id) {
					$model->setExtends( $internal_id, false );
				}
			}

			if(
				$model instanceof DataModels_Model_Related_Interface
			) {
				$model->findInternalMainModel();
			}
		}

		foreach( $imported_models as $parent ) {
			$ch_ids = [];

			foreach( $imported_models as $ch ) {
				if(
					$ch instanceof DataModels_Model_Related_Interface &&
					$ch->getInternalParentModelId()==$parent->getInternalId()
				) {
					$ch_ids[] = $ch->getInternalId();
				}
			}

			$parent->setInternalChildrenIds( $ch_ids );
		}

		/**
		 * @var DataModels_Model_Interface[] $imported_models
		 */
		foreach( $imported_models as $model ) {

			foreach( $model->getKeys() as $key ) {
				$property_names = [];

				foreach( $key->getPropertyNames() as $name ) {
					$id = $name;
					foreach($model->getProperties() as $property) {
						if($property->getName()==$name) {
							$id = $property->getInternalId();
							break;
						}
					}

					$property_names[] = $id;
				}

				$key->setPropertyNames( $property_names );

			}



			foreach( $model->getOuterRelations() as $relation ) {

				$relation->setRelatedToClass( DataModels::getModelInternalId( $relation->getRelatedDataModelClassName() ) );

				$related_class_name = $relation->getRelatedDataModelClassName();

				$this_definition = $model;
				$related_definition = DataModels::getModel( $related_class_name );
				if(!$related_definition) {
					continue;
				}


				$join = [];
				foreach( $relation->getJoinBy() as $j_b ) {
					$this_property_name = $j_b->getThisPropertyName();
					$related_property_name = $j_b->getRelatedPropertyName();

					foreach( $this_definition->getProperties() as $t_p ) {
						if($t_p->getName()==$this_property_name) {
							$this_property_name = $t_p->getInternalId();
							break;
						}
					}

					foreach( $related_definition->getProperties() as $r_p ) {
						if($r_p->getName()==$related_property_name) {
							$related_property_name = $r_p->getInternalId();
							break;
						}
					}

					$join[$this_property_name] = $related_property_name;
				}

				$relation->setJoinBy( $join );
			}



			foreach( $model->getProperties() as $property ) {

				if($property->isInherited()) {
					$ih_model = DataModels::getModel( DataModels::getModelInternalId( $property->getInheritedModelId() ) );
					if( $ih_model ) {
						$property->setInheritedModelId( $ih_model->getInternalId() );

						foreach( $ih_model->getProperties() as $ih_property ) {
							if($ih_property->getName()==$property->getInheritedPropertyId()) {
								$property->setInheritedPropertyId( $ih_property->getInternalId() );

								break;
							}
						}
					}
				}

				if( $property instanceof DataModels_Property_DataModel ) {
					$property->setDataModelClassName( DataModels::getModelInternalId( $property->getDataModelClass() ) );
				}

				if(
					$model instanceof DataModels_Model_Related_Interface &&
					$property instanceof DataModels_Property_RelatedTmp
				) {

					[$related_to_model, $related_to_property] = $model->parseRelatedTo( $property->getRelatedTo() );

					if(!$related_to_property || !$related_to_model) {
						$model->removeProperty( $property->getInternalId() );
					} else {
						/**
						 * @var DataModels_Model $related_to_model
						 * @var DataModels_Property $related_to_property
						 * @var DataModels_Property $new_property
						 */
						$new_property = clone $related_to_property;

						$new_property->setRelatedToClassName( $related_to_model->getInternalId() );
						$new_property->setRelatedToPropertyName( $related_to_property->getInternalId() );

						$new_property->setName( $property->getName() );
						$new_property->setInternalPriority( $property->getInternalPriority() );
						$new_property->setInternalId( $property->getInternalId() );

						$new_property->setIsInherited( $property->isInherited() );
						$new_property->setInheritedModelId( $property->getInheritedModelId() );
						$new_property->setInheritedPropertyId( $property->getInheritedPropertyId() );
						$new_property->setOverload( $property->isOverload() );

						$model->replaceProperty( $new_property );
					}

				}
			}
		}

		if($updated) {
			static::check();
			static::save();
		}
	}

}