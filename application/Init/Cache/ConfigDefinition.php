<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Config_Definition;
use Jet\SysConf_Jet;

if(SysConf_Jet::CACHE_CONFIG_DEFINITION_LOAD()) {
	Config_Definition::enableCacheLoad( function( $class ) {
		return Cache_ConfigDefinition::load( $class );
	} );
}

if(SysConf_Jet::CACHE_CONFIG_DEFINITION_SAVE()) {
	Config_Definition::enableCacheSave( function( $class, $data ) {
		Cache_ConfigDefinition::save( $class, $data );
	} );
}
