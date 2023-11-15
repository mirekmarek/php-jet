<?php
namespace Jet;

require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Main.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Debug.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/MVC.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Translator.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Http.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Autoloader.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/Mailing.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/PackageCreator/CSS.php';
require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet/PackageCreator/JavaScript.php';

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );
SysConf_Jet_Http::setHideRequest( true );

SysConf_Jet_Mailing::setTemplatesDir( SysConf_Path::getApplication().'email-templates/' );

SysConf_Jet_Debug::setDevelMode( true );

if( SysConf_Jet_Debug::getDevelMode() ) {
	//Dev configuration
	SysConf_Jet_Debug::setProfilerEnabled( false );

	SysConf_Jet_PackageCreator_CSS::setEnabled( false );
	SysConf_Jet_PackageCreator_JavaScript::setEnabled( false );

	SysConf_Jet_MVC::setCacheEnabled( false );
	SysConf_Jet_Autoloader::setCacheEnabled( false );
	SysConf_Jet_Translator::setAutoAppendUnknownPhrase( true );
} else {
	//Production configuration
	SysConf_Jet_Debug::setProfilerEnabled( false );

	SysConf_Jet_PackageCreator_CSS::setEnabled( true );
	SysConf_Jet_PackageCreator_JavaScript::setEnabled( true );

	SysConf_Jet_MVC::setCacheEnabled( true );
	SysConf_Jet_Autoloader::setCacheEnabled( true );
	SysConf_Jet_Translator::setAutoAppendUnknownPhrase( false );
}
