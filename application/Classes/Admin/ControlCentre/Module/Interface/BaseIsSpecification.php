<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\MVC_Base_LocalizedData_Interface;

interface Admin_ControlCentre_Module_Interface_BaseIsSpecification extends Admin_ControlCentre_Module_Interface {
	
	public function getSpecificationIdByBase( MVC_Base_LocalizedData_Interface $base ) : string;
	
	public function getBaseBySpecificationId( string $specification_id ) : ?MVC_Base_LocalizedData_Interface;
	
}