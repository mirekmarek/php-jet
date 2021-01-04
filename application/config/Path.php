<?php
use Jet\SysConf_Path;

$base = dirname( dirname( __DIR__ ) ).'/';
$library = $base.'library/';
$application =  $base.'application/';

require_once $library.'Jet/SysConf/Path.php';

SysConf_Path::setLibrary( $library );

SysConf_Path::setBase( $base );

SysConf_Path::setSites( $base . 'sites/' );
SysConf_Path::setPublic( $base . 'public/' );
SysConf_Path::setLogs( $base . 'logs/' );
SysConf_Path::setTmp( $base . 'tmp/' );
SysConf_Path::setCache( $base . 'cache/' );

SysConf_Path::setApplication( $application );
SysConf_Path::setConfig( $application . 'config/' );
SysConf_Path::setMenus( $application . 'menus/' );
SysConf_Path::setData( $application . 'data/' );
SysConf_Path::setDictionaries( $application . 'dictionaries/' );