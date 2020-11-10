<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


interface DataModels_Model_Related_Interface extends DataModels_Model_Interface {

	/**
	 * @param DataModels_Model_Interface $parent
	 */
	public function setInternalParentModel(DataModels_Model_Interface $parent );

	/**
	 * @return string
	 */
	public function getInternalParentModelId();

	/**
	 * @return DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN
	 */
	public function getInternalParentModel();


	/**
	 * @return string
	 */
	public function getInternalMainModelId();

	/**
	 * @return DataModels_Model
	 */
	public function getInternalMainModel();

	/**
	 * @param string $internal_parent_model_id
	 */
	public function setInternalParentModelId( $internal_parent_model_id );

	/**
	 *
	 */
	public function findInternalMainModel();

	/**
	 *
	 */
	public function checkIdProperties();

	/**
	 *
	 */
	public function regenerateRelatedPropertyNames();

	/**
	 * @param string $related_to
	 * @return array
	 */
	public function parseRelatedTo( $related_to );

}
