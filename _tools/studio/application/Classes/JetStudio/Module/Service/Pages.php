<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;


use Jet\MVC_Page_Interface;

interface JetStudio_Module_Service_Pages
{
	public function editPage( MVC_Page_Interface $page ) : string;
}