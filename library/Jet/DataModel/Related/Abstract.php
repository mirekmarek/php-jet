<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

abstract class DataModel_Related_Abstract extends DataModel {

	/**
	 * @var string|null
	 */
	protected static $____data_model_definition_class_name = null;


	/**
	 * @throws DataModel_Exception
	 *
	 * @return string
	 */
	public static function getParentModelClassName() {
		$parent_model_class_name = Object_Reflection::get( get_called_class(), 'data_model_parent_model_class_name', '' );

		if(!$parent_model_class_name) {
			throw new DataModel_Exception(
				'Related DataModel parent class is not defined. Class: \''.get_called_class().'\' Please define it in the class doc comment. Example: @JetDataModel:parent_model_class_name = \'Some\\ParentClass\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $parent_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getParentModelDefinition() {
		return $this->getDataModelDefinition()
				->getParentRelatedModelDefinition();

	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getMainModelDefinition() {
		return $this->getDataModelDefinition()
				->getMainModelDefinition();
	}

	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	final public static function getBackendType() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getParentModelClassName() );

		return $class_name::getBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return array
	 */
	final public static function getBackendConfig() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getParentModelClassName() );

		return $class_name::getBackendConfig();
	}


	/**
	 *
	 * @return bool
	 */
	final public static function getCacheEnabled() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getParentModelClassName() );

		return $class_name::getCacheEnabled();
	}

	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	final public static  function getCacheBackendType() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getParentModelClassName() );

		return $class_name::getCacheBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return array
	 */
	final public static function getCacheBackendConfig() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getParentModelClassName() );

		return $class_name::getCacheBackendConfig();
	}

	/**
	 * Loads DataModel.
	 *
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance (do nothing)
	 *
	 * @throws DataModel_Exception
	 * @return DataModel
	 */
	public function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {
	}


	/**
	 * @throws DataModel_Exception
	 */
	public function save() {

		$main_class = $this->getDataModelDefinition()->getMainModelDefinition()->getClassName();

		throw new DataModel_Exception(
			'Please use '.$main_class.'->save() ',
			DataModel_Exception::CODE_PERMISSION_DENIED
		);

	}


	/**
	 * Save data.
	 * CAUTION: Call validateProperties first!
	 *
	 * @param DataModel $main_model_instance (optional)
	 * @param DataModel_Related_Abstract $parent_model_instance (optional)
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function saveRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null ) {


		/**
		 * @var DataModel_Definition_Model_Related_1toN $definition
		 */
		$definition = $this->getDataModelDefinition();


		$main_ID = $main_model_instance->getID();

		$r_IDs = array();

		foreach($definition->getMainModelRelationIDProperties() as $r_property_name => $r_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $r_property
			 * @var DataModel_Definition_Property_Abstract $rt_property
			 */
			$rt_property = $r_property->getRelatedToProperty();
			$r_IDs[$r_property_name] = $main_ID[ $rt_property->getName() ];

		}


		if( $parent_model_instance ) {
			/**
			 * @var DataModel_ID_Abstract $parent_ID
			 * @var DataModel_Definition_Model_Related_Abstract $definition
			 */
			$parent_ID = $parent_model_instance->getID();

			foreach($definition->getParentModelRelationIDProperties() as $r_property_name => $r_property) {
				/**
				 * @var DataModel_Definition_Property_Abstract $r_property
				 * @var DataModel_Definition_Property_Abstract $rt_property
				 */
				$rt_property = $r_property->getRelatedToProperty();
				$r_IDs[$r_property_name] = $parent_ID[ $rt_property->getName() ];

			}

		}
		foreach($r_IDs as $property => $value) {
			if(
				$this->___data_model_saved &&
				$this->{$property}!=$value
			) {
				$this->___data_model_saved = false;
			}
			$this->{$property} = $value;
		}

		$this->_checkBeforeSave();


		$backend = $this->getBackendInstance();

		if( !$this->___data_model_saved ) {
			$operation = 'save';
		} else {
			$operation = 'update';
		}


		$this->{'_'.$operation}( $backend, $main_model_instance );

		$this->___data_model_saved = true;

	}

}