<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\SysConf_Path;

$project_base = dirname(dirname(dirname(dirname(__DIR__)))).'/';
$studio_base = dirname(dirname(__DIR__)).'/';
$studio_application =  $studio_base.'application/';
$project_application =  $project_base.'application/';

$library = $project_base.'library/';

require_once $library.'Jet/SysConf/Path.php';
require_once $studio_base.'application/Classes/ProjectConf/Path.php';

SysConf_Path::setLIBRARY( $library );

SysConf_Path::setBASE( $studio_base );
SysConf_Path::setPUBLIC( $studio_base . 'public/' );
SysConf_Path::setLOGS( $studio_base . 'logs/' );
SysConf_Path::setTMP( $studio_base . 'tmp/' );
SysConf_Path::setAPPLICATION( $studio_application );
SysConf_Path::setCONFIG( $studio_application . 'config/' );

SysConf_Path::setDATA( $studio_application . 'data/' );
SysConf_Path::setDICTIONARIES( $studio_application . 'dictionaries/' );




SysConf_Path::setSITES( $project_base . 'sites/' );
SysConf_Path::setCACHE( $project_base . 'cache/' );

ProjectConf_Path::setBASE( $project_base );
SysConf_Path::setMENUS( $project_application.'menus/' );

ProjectConf_Path::setAPPLICATION( $project_application );
ProjectConf_Path::setAPPLICATION_CLASSES( $project_application.'Classes/' );
ProjectConf_Path::setAPPLICATION_MODULES( $project_application.'Modules/' );

ProjectConf_Path::setSITES( $project_base . 'sites/' );
ProjectConf_Path::setCONFIG( $project_application . 'config/' );


ProjectConf_Path::setPUBLIC( $project_application . 'public/' );
ProjectConf_Path::setLOGS( $project_application . 'logs/' );
ProjectConf_Path::setTMP( $project_application . 'tmp/' );
ProjectConf_Path::setCACHE( $project_application . 'cache/' );


ProjectConf_Path::setDATA( $project_application . 'data/' );
ProjectConf_Path::setDICTIONARIES( $project_application . 'dictionaries/' );

ProjectConf_Path::setTEMPLATES( $studio_base . 'templates/' );
