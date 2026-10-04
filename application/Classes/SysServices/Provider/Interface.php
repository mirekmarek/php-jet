<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


interface SysServices_Provider_Interface {
	
	/**
	 * @return SysServices_Definition[]
	 */
	public function getSysServicesDefinitions() : array;
}