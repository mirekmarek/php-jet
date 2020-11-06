<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Definition;

if(JET_CACHE_DATAMODEL_DEFINITION_LOAD) {
	DataModel_Definition::enableCacheLoad( function( $class ) {
		return Cache_DataModelDefinition::load( $class );
	} );
}

if(JET_CACHE_DATAMODEL_DEFINITION_SAVE) {
	DataModel_Definition::enableCacheSave( function( $class, $data ) {
		Cache_DataModelDefinition::save( $class, $data );
	} );
}
