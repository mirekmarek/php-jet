<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 */
trait DataModel_Definition_Model_Related_Trait
{
	use DataModel_Definition_Model_Trait;

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getRelevantParentModel(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	{
		$parent_class = $this->parent_model_class;
		if( !$parent_class ) {
			$parent_class = $this->main_model_class_name;
		}

		$parent_class = DataModels::getClass( $parent_class );

		return $parent_class->getDefinition();
	}


	/**
	 * @param DataModel_Definition_Model_Interface $parent
	 */
	public function setParentModel( DataModel_Definition_Model_Interface $parent ): void
	{
		if( $parent instanceof DataModel_Definition_Model_Related_Interface ) {
			$this->parent_model_class = $parent->getClassName();
		} else {
			$this->main_model_class_name = $parent->getClassName();
		}
	}


	/**
	 * @return string
	 */
	public function getParentModelClassName(): string
	{
		return $this->parent_model_class;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentModel(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	{
		return DataModels::getClass( $this->parent_model_class )->getDefinition();
	}


	/**
	 * @return string
	 */
	public function getMainModelClassName(): string
	{
		return $this->main_model_class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getMainModel(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	{
		return DataModels::getClass( $this->main_model_class_name )->getDefinition();
	}

}