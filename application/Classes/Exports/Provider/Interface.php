<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


interface Exports_Provider_Interface
{
	/**
	 * @return Exports_Definition[]
	 */
	public function getExportsDefinitions() : array;
}