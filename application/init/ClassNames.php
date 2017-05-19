<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//Debug_Profiler::blockStart('INIT - ClassNames');

Application_Factory::setModuleManifestClassName( 'JetExampleApp\Application_Module_Manifest' );
Mvc_Factory::setPageClassName( 'JetExampleApp\Mvc_Page' );

//Debug_Profiler::blockEnd('INIT - ClassNames');
