<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Services;
use Jet\SysConf_Path;

class Application_REST_Services extends Application_Services
{
	public const GROUP = 'REST';
	
	public static function getCfgFilePath(): string
	{
		return SysConf_Path::getConfig().'services/rest.php';
	}
	
	public static function Logger() : null|Application_Module|Application_REST_Services_Logger
	{
		return static::get( Application_REST_Services_Logger::class );
	}
	
	public static function AuthController() : Application_Module|Application_REST_Services_Auth_Controller
	{
		return static::get( Application_REST_Services_Auth_Controller::class );
	}
}