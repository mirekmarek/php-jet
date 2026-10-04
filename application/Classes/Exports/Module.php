<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\MVC_Base_LocalizedData_Interface;


abstract class Exports_Module extends Application_Module
{
	public function getCode() : string
	{
		$code = $this->getModuleManifest()->getName();
		
		$prefix = Exports::getModuleNamePrefix();
		
		return substr($code, strlen($prefix));
	}
	
	abstract public function getTitle() : string;
	
	abstract public function isAllowedForBase( MVC_Base_LocalizedData_Interface $base_localized ) : bool;
	
	/**
	 * @return Exports_Definition[]
	 */
	abstract public function getExportsDefinitions() : array;

}