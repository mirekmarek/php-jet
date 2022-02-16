<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public function getParentModelClassName() : string;

	/**
	 * @return string
	 */
	public function getMainModelClassName(): string;


}