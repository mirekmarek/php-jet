<?php
use Jet\SysConf_Path;

$base = dirname( dirname( __DIR__ ) ).'/';
$library = $base.'library/';
$application =  $base.'application/';

require_once $library.'Jet/SysConf/Path.php';

SysConf_Path::setLIBRARY( $library );

SysConf_Path::setBASE( $base );

SysConf_Path::setSITES( $base . 'sites/' );
SysConf_Path::setPUBLIC( $base . 'public/' );
SysConf_Path::setLOGS( $base . 'logs/' );
SysConf_Path::setTMP( $base . 'tmp/' );
SysConf_Path::setCACHE( $base . 'cache/' );

SysConf_Path::setAPPLICATION( $application );
SysConf_Path::setCONFIG( $application . 'config/' );
SysConf_Path::setMENUS( $application . 'menus/' );
SysConf_Path::setDATA( $application . 'data/' );
SysConf_Path::setDICTIONARIES( $application . 'dictionaries/' );
