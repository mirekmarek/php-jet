<?php
use Jet\SysConf_PATH;

$base = dirname( dirname( __DIR__ ) ).'/';
$library = $base.'library/';
$application =  $base.'application/';

require_once $library.'Jet/SysConf/PATH.php';

SysConf_PATH::setLIBRARY( $library );

SysConf_PATH::setBASE( $base );

SysConf_PATH::setSITES( $base . 'sites/' );
SysConf_PATH::setPUBLIC( $base . 'public/' );
SysConf_PATH::setLOGS( $base . 'logs/' );
SysConf_PATH::setTMP( $base . 'tmp/' );
SysConf_PATH::setCACHE( $base . 'cache/' );

SysConf_PATH::setAPPLICATION( $application );
SysConf_PATH::setCONFIG( $application . 'config/' );
SysConf_PATH::setMENUS( $application . 'menus/' );
SysConf_PATH::setDATA( $application . 'data/' );
SysConf_PATH::setDICTIONARIES( $application . 'dictionaries/' );
