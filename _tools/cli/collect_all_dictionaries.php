<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

require 'init/init.php';

foreach(Application_Modules::allModulesList() as $module_manifest ) {
	if( $module_manifest->isInstalled() ) {
		echo $module_manifest->getName().":\t";
		
		$ok = true;
		
		try {
			Translator::collectApplicationModuleDictionaries( $module_manifest );
		} catch( Exception $e ) {
			$ok = false;
			echo "\nERROR: ".$e->getMessage()."\n\n";
		}

		
		if( $ok ) {
			echo "OK\n";
		}
		
	}
	
}