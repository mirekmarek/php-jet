<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\SysConf_PATH;

$project_base = dirname(dirname(dirname(dirname(__DIR__)))).'/';
$studio_base = dirname(dirname(__DIR__)).'/';
$studio_application =  $studio_base.'application/';
$project_application =  $project_base.'application/';

$library = $project_base.'library/';

require_once $library.'Jet/SysConf/PATH.php';
require_once $studio_base.'application/Classes/ProjectConf/PATH.php';

SysConf_PATH::setLIBRARY( $library );

SysConf_PATH::setBASE( $studio_base );
SysConf_PATH::setPUBLIC( $studio_base . 'public/' );
SysConf_PATH::setLOGS( $studio_base . 'logs/' );
SysConf_PATH::setTMP( $studio_base . 'tmp/' );
SysConf_PATH::setCACHE( $studio_base . 'cache/' );
SysConf_PATH::setAPPLICATION( $studio_application );
SysConf_PATH::setCONFIG( $studio_application . 'config/' );

SysConf_PATH::setDATA( $studio_application . 'data/' );
SysConf_PATH::setDICTIONARIES( $studio_application . 'dictionaries/' );




SysConf_PATH::setSITES( $project_base . 'sites/' );

ProjectConf_PATH::setBASE( $project_base );

ProjectConf_PATH::setAPPLICATION( $project_application );
ProjectConf_PATH::setAPPLICATION_CLASSES( $project_application.'Classes/' );
ProjectConf_PATH::setAPPLICATION_MODULES( $project_application.'Modules/' );

ProjectConf_PATH::setSITES( $project_base . 'sites/' );
ProjectConf_PATH::setCONFIG( $project_application . 'config/' );

ProjectConf_PATH::setPUBLIC( $project_application . 'public/' );
ProjectConf_PATH::setLOGS( $project_application . 'logs/' );
ProjectConf_PATH::setTMP( $project_application . 'tmp/' );
ProjectConf_PATH::setCACHE( $project_application . 'cache/' );


ProjectConf_PATH::setDATA( $project_application . 'data/' );
ProjectConf_PATH::setDICTIONARIES( $project_application . 'dictionaries/' );

ProjectConf_PATH::setTEMPLATES( $studio_base . 'templates/' );
