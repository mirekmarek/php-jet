<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Debug;
use Jet\Debug_VarDump;

$controller = new class {
	public function __construct()
	{
		Debug_VarDump::setDisplayer( function() {
			$this->displayer( Debug_VarDump::getVarDumps() );
		} );
	}
	
	
	/**
	 * @param Debug_VarDump[] $var_dumps
	 * @return void
	 */
	public function displayer( array $var_dumps ) : void
	{
		if( Debug::getOutputIsHTML() ) {
			require __DIR__ . '/views/bar.phtml';
		}
	}
};

