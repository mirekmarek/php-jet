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

abstract class DataModel_Related_1to1 extends DataModel_Related_Abstract {

	/**
	 *
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_1to1
	 */
	public static function getDataModelDefinition( $class_name='' )  {
		if($class_name) {
			return DataModel::getDataModelDefinition($class_name);
		}

		$class = get_called_class();

		if( !isset(DataModel::$___data_model_definitions[$class])) {

			DataModel::$___data_model_definitions[$class] = new DataModel_Definition_Model_Related_1to1( $class );
		}
		return DataModel::$___data_model_definitions[$class];
	}


	/**
	 * Loads DataModel.
	 *
	 * @param DataModel $main_model_instance (MANDATORY!)
	 * @param DataModel_Related_Abstract $parent_model_instance
	 *
	 * @throws DataModel_Exception
	 * @return DataModel
	 */
	public function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {

		$model_definition = $this->getDataModelDefinition();

		$query = $main_model_instance->getIDQuery(  $model_definition->getMainModelRelationIDProperties() );

		if($parent_model_instance) {
			$query->getWhere()->attach(
				$parent_model_instance->getIDQuery(  $model_definition->getParentModelRelationIDProperties() )->getWhere()
			);
		}

		$query->setSelect( $model_definition->getProperties() );

		$data = $this->getBackendInstance()->fetchRow( $query );

		if(!$data) {
			return null;
		}

		$loaded_instance = $this->_load_dataToInstance( $data, $main_model_instance );

		return $loaded_instance;

	}

}