<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\MVC_Page_Interface;

interface Admin_ControlCentre_Module_Interface_WebIsSpecification extends Admin_ControlCentre_Module_Interface {
	public function getSpecificationIdByPage( MVC_Page_Interface $page ) : string;
}