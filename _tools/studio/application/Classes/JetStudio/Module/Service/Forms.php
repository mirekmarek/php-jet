<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;


interface JetStudio_Module_Service_Forms
{
	public function generateViewFile( string $class_name, string $target_file ) : void;
	
	public function getPropertyEditURL( string $class_name, string $property_name ) : string;
}