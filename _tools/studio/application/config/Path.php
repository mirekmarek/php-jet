<?php
/**
 *
 * @copyright Copyright (c) Miroslav7 Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudio;

use Jet\SysConf_Path;

$project_base = dirname( __DIR__, 4 ) .'/';
$studio_base = dirname( __DIR__, 2 ) .'/';
$studio_application =  $studio_base.'application/';
$project_application =  $project_base.'application/';

$library = $project_base.'library/';

require_once $library.'Jet/SysConf/Path.php';
require_once $studio_base.'application/Classes/ProjectConf/Path.php';

SysConf_Path::setLibrary( $library );

SysConf_Path::setBase( $studio_base );
SysConf_Path::setCss( $studio_base . 'css/' );
SysConf_Path::setJs( $studio_base . 'js/' );
SysConf_Path::setImages( $studio_base . 'images/' );
SysConf_Path::setLogs( $studio_base . 'logs/' );
SysConf_Path::setTmp( $studio_base . 'tmp/' );
SysConf_Path::setApplication( $studio_application );
SysConf_Path::setConfig( $studio_application . 'config/' );

SysConf_Path::setData( $studio_application . 'data/' );
SysConf_Path::setDictionaries( $studio_application . 'dictionaries/' );




SysConf_Path::setCache( $project_base . 'cache/' );

ProjectConf_Path::setRoot( $project_base );
SysConf_Path::setBases( $project_application . 'bases/' );
SysConf_Path::setMenus( $project_application . 'menus/' );

ProjectConf_Path::setApplication( $project_application );
ProjectConf_Path::setApplicationClasses( $project_application.'Classes/' );
ProjectConf_Path::setApplicationModules( $project_application.'Modules/' );

ProjectConf_Path::setBases( $project_base . 'bases/' );
ProjectConf_Path::setConfig( $project_application . 'config/' );


ProjectConf_Path::setLogs( $project_application . 'logs/' );
ProjectConf_Path::setTmp( $project_application . 'tmp/' );
ProjectConf_Path::setCache( $project_application . 'cache/' );


ProjectConf_Path::setData( $project_application . 'data/' );
ProjectConf_Path::setDictionaries( $project_application . 'dictionaries/' );

ProjectConf_Path::setTemplates( $studio_base . 'templates/' );

SysConf_Path::setModules( ProjectConf_Path::getApplication().'Modules/' );