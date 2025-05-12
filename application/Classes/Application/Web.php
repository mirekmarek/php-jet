<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Logger;

use Jet\MVC;
use Jet\MVC_Base_Interface;
use Jet\MVC_Router;

use Jet\Auth;
use Jet\SysConf_Jet_ErrorPages;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_MVC;
use Jet\SysConf_Jet_UI;

/**
 *
 */
class Application_Web
{
	/**
	 * @return string
	 */
	public static function getBaseId(): string
	{
		return 'web';
	}

	/**
	 * @return MVC_Base_Interface
	 */
	public static function getBase(): MVC_Base_Interface
	{
		return MVC::getBase( static::getBaseId() );
	}


	/**
	 * @param MVC_Router $router
	 */
	public static function init( MVC_Router $router ): void
	{
		SysConf_Jet_MVC::setUseModulePages( false );
		
		
		Logger::setLoggerProvider( function() : ?Application_Web_Services_Logger {
			return Application_Web_Services::Logger();
		} );
		
		Auth::setControllerProvider( function() : Application_Web_Services_Auth_Controller {
			return Application_Web_Services::AuthController();
		} );

		SysConf_Jet_UI::setViewsDir( $router->getBase()->getViewsPath() . 'ui/' );
		SysConf_Jet_Form::setDefaultViewsDir( $router->getBase()->getViewsPath() . 'form/' );
		SysConf_Jet_ErrorPages::setErrorPagesDir( $router->getBase()->getPagesDataPath( $router->getLocale() ) );
		
		if($router->tryDirectFiles([
			'robots.txt',
			'security.txt'
		])) {
			/** @noinspection PhpUnnecessaryStopStatementInspection */
			return;
		}
		
	}

}