<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
interface DataModel_Definition_Model_Related_Interface extends DataModel_Definition_Model_Interface
{
	/**
	 * @param DataModel_Definition_Model_Interface $parent
	 */
	public function setParentModel( DataModel_Definition_Model_Interface $parent );

	/**
	 * @return string
	 */
	public function getParentModelClassName();

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getParentModel();


	/**
	 * @return string
	 */
	public function getMainModelClassName();

	/**
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModel();

}