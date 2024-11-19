<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;


interface JetStudio_Module_Service_DataModel
{
	/**
	 * @return ClassMetaInfo[]
	 */
	public function getDataModelClasses( bool $main_only=true ) : array;
}