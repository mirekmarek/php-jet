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
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_Abstract( $data_model_class_name );
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
	abstract function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  );

	/**
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance
	 */
	abstract function wakeUp( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  );


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
			 */
			$r_IDs[$r_property_name] = $main_ID[ $r_property->getRelatedToPropertyName() ];
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
				 */
				$r_IDs[$r_property_name] = $parent_ID[ $r_property->getRelatedToPropertyName() ];

			}

		}

		foreach($r_IDs as $property => $value) {
			if(
				$this->getIsSaved() &&
				$this->{$property}!=$value
			) {
				$this->resetID();
				$this->setIsNew();

				break;
			}
		}

		foreach($r_IDs as $property => $value) {
			$this->{$property} = $value;
		}


		$this->generateID();

		$this->_checkBeforeSave();


		$backend = $this->getBackendInstance();


		if( !$this->getIsSaved() ) {
			$operation = 'save';
		} else {
			$operation = 'update';
		}


		$this->{'_'.$operation}( $backend, $main_model_instance );

		$this->setIsSaved();

	}


}