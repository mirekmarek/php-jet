<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet_Debug;
use Jet\SysConf_Jet_MVC;
use Jet\SysConf_Jet_Http;
use Jet\SysConf_Jet_Translator;
use Jet\SysConf_Jet_Autoloader;
use Jet\SysConf_Jet_PackageCreator_CSS;
use Jet\SysConf_Jet_PackageCreator_JavaScript;

require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/Main.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/Debug.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/MVC.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/Http.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/Translator.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet/Autoloader.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/PackageCreator/CSS.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/PackageCreator/JavaScript.php';

SysConf_Jet_Debug::setDevelMode( true );
SysConf_Jet_Debug::setProfilerEnabled( false );

SysConf_Jet_PackageCreator_CSS::setEnabled( false );
SysConf_Jet_PackageCreator_JavaScript::setEnabled( false );

SysConf_Jet_Translator::setAutoAppendUnknownPhrase( true );
SysConf_Jet_Http::setHideRequest( true );

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );

SysConf_Jet_MVC::setCacheEnabled( false );
SysConf_Jet_Autoloader::setCacheEnabled( false );
