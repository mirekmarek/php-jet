<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModelImport;

use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Manifest;

class Main extends JetStudio_Module
{
	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
	}
	
}
