<?php
namespace Jet;

$base = dirname( __DIR__, 2 ) .'/';
$library = $base.'library/';
$application =  $base.'application/';

require_once $library.'Jet/SysConf/Path.php';

SysConf_Path::setLibrary( $library );

SysConf_Path::setBase( $base );

SysConf_Path::setCss( $base . 'css/' );
SysConf_Path::setJs( $base . 'js/' );
SysConf_Path::setImages( $base . 'images/' );
SysConf_Path::setLogs( $base . 'logs/' );
SysConf_Path::setTmp( $base . 'tmp/' );
SysConf_Path::setCache( $base . 'cache/' );

SysConf_Path::setApplication( $application );
SysConf_Path::setBases( $application . 'bases/' );
SysConf_Path::setModules( $application . 'Modules/' );
SysConf_Path::setConfig( $application . 'config/' );
SysConf_Path::setMenus( $application . 'menus/' );
SysConf_Path::setData( $application . 'data/' );
SysConf_Path::setDictionaries( $application . 'dictionaries/' );
