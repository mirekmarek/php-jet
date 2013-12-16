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
	 * @var string|null
	 */
	protected static $__data_model_parent_model_class_name = null;

	/**
	 * @var string
	 */
	protected static $____data_model_definition_class_name = "Jet\\DataModel_Definition_Model_Related_1to1";



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
			return NULL;
		}

		$loaded_instance = $this->_load_dataToInstance( $data, $main_model_instance );

		return $loaded_instance;

	}

}