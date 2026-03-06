<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


interface Entity_InputCatcher_Interface
{
	
	/**
	 * @return Entity_InputCatcher_Definition_PropertyInputCatcher[]
	 */
	public function getPropertyInputCatchersDefinition() : array;
	
	/**
	 * @param array<string|mixed>|Data_Array $input
	 * @return void
	 */
	public function catchInput( array|Data_Array $input ) : void;

}