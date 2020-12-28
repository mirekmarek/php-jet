<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 */
trait DataModel_Definition_Model_Related_Trait
{
	use DataModel_Definition_Model_Trait;

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getRelevantParentModel() : DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	{
		$parent_class = $this->parent_model_class;
		if(!$parent_class) {
			$parent_class = $this->main_model_class_name;
		}

		$parent_class = DataModels::getClass( $parent_class );

		return $parent_class->getDefinition();
	}


	/**
	 * @param DataModel_Definition_Model_Interface $parent
	 */
	public function setParentModel( DataModel_Definition_Model_Interface $parent ) : void
	{
		if($parent instanceof DataModel_Definition_Model_Related_Interface) {
			$this->parent_model_class = $parent->getClassName();
		} else {
			$this->main_model_class_name = $parent->getClassName();
		}
	}


	/**
	 * @return string
	 */
	public function getParentModelClassName() : string
	{
		return $this->parent_model_class;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getParentModel() : DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	{
		return DataModels::getClass($this->parent_model_class)->getDefinition();
	}


	/**
	 * @return string
	 */
	public function getMainModelClassName() : string
	{
		return $this->main_model_class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getMainModel() : DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	{
		return DataModels::getClass($this->main_model_class_name)->getDefinition();
	}

}