<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


trait DataModels_Model_Related_Trait
{
	use DataModels_Model_Trait;

	/**
	 * @var string
	 */
	protected $internal_main_model_id = '';

	/**
	 * @var string
	 */
	protected $internal_parent_model_id = '';

	/**
	 * @return DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	public function getRelevantParentModel()
	{
		$parent = $this->getInternalParentModel();
		if(!$parent) {
			$parent = $this->getInternalMainModel();
		}

		return $parent;
	}

	/**
	 * @param string $model_name
	 */
	public function setModelName($model_name)
	{
		/**
		 * @var DataModels_Model $this
		 */
		if($this->model_name==$model_name) {
			return;
		}

		$old_value = $this->model_name;
		$len = strlen($old_value);

		$this->model_name = $model_name;

		foreach( $this->getChildren() as $child ) {
			if(substr($child->getModelName(), 0, $len)==$old_value) {
				$child->setModelName( $model_name.substr($child->getModelName(), $len) );
			}
		}

		$parent = $this->getRelevantParentModel();

		if($parent) {
			foreach( $parent->getProperties() as $property ) {
				if( !$property instanceof DataModels_Property_DataModel) {
					continue;
				}

				if($property->getDataModelClassName()!=$this->getInternalId()) {
					continue;
				}

				$property->setName($this->generateRelatedPropertyName());
			}
		}

		foreach( $this->getChildren() as $ch ) {
			$ch->regenerateRelatedPropertyNames();
		}

	}


	/**
	 * @param DataModels_Model_Interface $parent
	 */
	public function setInternalParentModel(DataModels_Model_Interface $parent )
	{
		/**
		 * @var DataModels_Model_Related_Trait|DataModels_Model_Related_Interface $this
		 */

		if($parent instanceof DataModels_Model_Related_Interface) {
			/**
			 * @var DataModels_Model_Related_Interface $parent
			 */
			$this->internal_main_model_id = $parent->getInternalMainModelId();
			$this->internal_parent_model_id = $parent->getInternalId();
		} else {
			$this->internal_main_model_id = $parent->getInternalId();
		}

		$parent->addChild( $this );


		$related_property = new DataModels_Property_DataModel();
		$related_property->setName( $this->generateRelatedPropertyName() );
		$related_property->setDataModelClassName( $this->getInternalId() );

		$parent->addProperty( $related_property );
	}

	/**
	 * @return string
	 */
	public function generateRelatedPropertyName()
	{

		$parent = $this->getRelevantParentModel();


		$related_property_name = $this->getModelName();
		$parent_model_name = $parent->getModelName();

		$prefix = substr( $this->getModelName(), 0, strlen($parent_model_name) );


		if( $prefix==$parent_model_name ) {
			$related_property_name = substr($this->getModelName(), strlen($prefix) );
			$related_property_name = ltrim($related_property_name, '_');
		}

		return $related_property_name;
	}

	/**
	 * @return string
	 */
	public function getInternalParentModelId()
	{
		return $this->internal_parent_model_id;
	}

	/**
	 * @return DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	public function getInternalParentModel()
	{
		return DataModels::getModel($this->internal_parent_model_id);
	}


	/**
	 * @return string
	 */
	public function getInternalMainModelId()
	{
		return $this->internal_main_model_id;
	}

	/**
	 * @return DataModels_Model
	 */
	public function getInternalMainModel()
	{
		return DataModels::getModel($this->internal_main_model_id);
	}

	/**
	 *
	 */
	public function checkIdProperties()
	{
		/**
		 * @var DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN $this
		 * @var DataModels_Property[] $_related_properties
		 */

		$_related_properties = [];

		foreach( $this->getProperties() as $property ) {
			if($property->getRelatedToClassName()) {
				$key = $property->getRelatedToClassName().'.'.$property->getRelatedToPropertyName();

				$_related_properties[$key] = $property;
				$this->removeProperty( $property->getInternalId() );
			}
		}


		$main_model = $this->getInternalMainModel();

		foreach( $main_model->getProperties() as $parent_property ) {
			if( !$parent_property->getIsId() ) {
				continue;
			}


			$class = get_class($parent_property);

			$key = 'main:'.$main_model->getInternalId().'.'.$parent_property->getInternalId();

			if(
				!isset($_related_properties[$key]) ||
				get_class($_related_properties[$key])!=$class
			) {
				/**
				 * @var DataModels_Property_Interface $r_id
				 */
				$r_id = new $class();

				if(isset($_related_properties[$key])) {
					$name = $_related_properties[$key]->getName();
				} else {
					$name = $main_model->getModelName().'_'.$parent_property->getName();
				}

				$r_id->setName( $name );
				$r_id->setIsId(false);
				$r_id->setRelatedToClassName( 'main:'.$main_model->getInternalId() );
				$r_id->setRelatedToPropertyName( $parent_property->getInternalId() );
			} else {
				$r_id = $_related_properties[$key];
			}

			$this->addProperty( $r_id );
		}

		$parent_model = $this->getInternalParentModel();
		if(
			$parent_model &&
			$parent_model->getInternalId()!=$main_model->getInternalId()
		) {
			foreach( $parent_model->getProperties() as $parent_property ) {
				if(
					!$parent_property->getIsId() ||
					$parent_property->getRelatedToClassName()
				) {
					continue;
				}

				$class = get_class($parent_property);

				$key = 'parent:'.$parent_model->getInternalId().'.'.$parent_property->getInternalId();

				if(
					!isset($_related_properties[$key]) ||
					get_class($_related_properties[$key])!=$class
				) {
					/**
					 * @var DataModels_Property_Interface $r_id
					 */
					$r_id = new $class();

					if(isset($_related_properties[$key])) {
						$name = $_related_properties[$key]->getName();
					} else {
						$name = $parent_model->getModelName().'_'.$parent_property->getName();
					}

					$r_id->setName( $name );
					$r_id->setIsId(false);
					$r_id->setRelatedToClassName( 'parent:'.$parent_model->getInternalId() );
					$r_id->setRelatedToPropertyName( $parent_property->getInternalId() );
				} else {
					$r_id = $_related_properties[$key];
				}


				$this->addProperty( $r_id );
			}
		}
	}

	/**
	 *
	 */
	public function regenerateRelatedPropertyNames()
	{
		/**
		 * @var DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN $this
		 */

		$main_model = $this->getInternalMainModel();

		foreach( $main_model->getProperties() as $parent_property ) {
			if(
				!$parent_property->getIsId() ||
				$parent_property->getRelatedToClassName()
			) {
				continue;
			}

			foreach( $this->getProperties() as $property ) {
				if(
					$property->getRelatedToClassName()=='main:'.$main_model->getInternalId() &&
					$property->getRelatedToPropertyName()==$parent_property->getInternalId()
				) {
					$property->setName( $main_model->getModelName().'_'.$parent_property->getName() );
				}
			}

		}

		$parent_model = $this->getInternalParentModel();
		if($parent_model) {
			foreach( $parent_model->getProperties() as $parent_property ) {
				if(
					!$parent_property->getIsId() ||
					$parent_property->getRelatedToClassName()
				) {
					continue;
				}

				foreach( $this->getProperties() as $property ) {
					if(
						$property->getRelatedToClassName()=='parent:'.$parent_model->getInternalId() &&
						$property->getRelatedToPropertyName()==$parent_property->getInternalId()
					) {
						$property->setName( $parent_model->getModelName().'_'.$parent_property->getName() );
					}
				}

			}

		}


		foreach( $this->getChildren() as $ch ) {
			$ch->regenerateRelatedPropertyNames();
		}
	}

	/**
	 *
	 */
	public function delete()
	{
		$this->getRelevantParentModel()->removeChild( $this );

		foreach( $this->internal_children_ids as $i=>$id ) {
			DataModels::getModel( $id )->delete();
			unset($this->internal_children_ids[$i]);
		}

		DataModels::deleteModel( $this->getInternalId() );
	}

	/**
	 * @param string $internal_parent_model_id
	 */
	public function setInternalParentModelId( $internal_parent_model_id )
	{
		$this->internal_parent_model_id = $internal_parent_model_id;
	}

	/**
	 * @param string $internal_main_model_id
	 */
	public function setInternalMainModelId( $internal_main_model_id )
	{
		$this->internal_main_model_id = $internal_main_model_id;
	}

	/**
	 *
	 */
	public function findInternalMainModel()
	{

		$main_model = DataModels::getModel( $this->getInternalParentModelId() );

		while(
			!($main_model instanceof DataModels_Model) &&
			$main_model->getInternalParentModelId() &&
			$parent=DataModels::getModel( $main_model->getInternalParentModelId())
		) {
			$main_model = $parent;
		}

		$this->internal_main_model_id = $main_model->getInternalId();
	}

	/**
	 * @param string $related_to
	 *
	 * @return array
	 */
	public function parseRelatedTo( $related_to )
	{
		list($what, $property_name) = explode('.', $related_to);

		$related_to_model = null;
		$related_to_property = null;

		if($what=='main') {
			$related_to_model = DataModels::getModel( $this->getInternalMainModelId() );
		} else {
			$related_to_model = DataModels::getModel( $this->getInternalParentModelId() );
		}

		if($related_to_model) {
			foreach( $related_to_model->getProperties() as $property ) {
				if($property_name==$property->getName()) {
					$related_to_property = $property;
					break;
				}
			}
		}

		return [$related_to_model, $related_to_property];
	}

}