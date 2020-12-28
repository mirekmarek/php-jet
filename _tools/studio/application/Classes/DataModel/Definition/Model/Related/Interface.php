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
	 * @return string
	 */
	public function getParentModelClassName();

	/**
	 * @return string
	 */
	public function getMainModelClassName() : string;


}