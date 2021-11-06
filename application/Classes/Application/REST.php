<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Logger;
use Jet\Mvc;
use Jet\Mvc_Base_Interface;
use Jet\Mvc_Router;
use Jet\Auth;

/**
 *
 */
class Application_REST
{
	/**
	 * @return string
	 */
	public static function getBaseId(): string
	{
		return 'rest';
	}

	/**
	 * @return Mvc_Base_Interface
	 */
	public static function getBase(): Mvc_Base_Interface
	{
		return Mvc::getBase( static::getBaseId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router ): void
	{
		Logger::setLogger( new Logger_REST() );
		Auth::setController( new Auth_Controller_REST() );
	}

}