<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//Debug_Profiler::blockStart('INIT - ClassNames');

Application_Factory::setModuleManifestClassName( 'JetApplication\Application_Module_Manifest' );
Mvc_Factory::setPageClassName( 'JetApplication\Mvc_Page' );

//Debug_Profiler::blockEnd('INIT - ClassNames');