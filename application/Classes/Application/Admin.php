<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Factory_MVC;
use Jet\Logger;
use Jet\MVC;
use Jet\MVC_Base_Interface;
use Jet\MVC_Router;
use Jet\Auth;
use Jet\SysConf_Jet_ErrorPages;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_UI;

/**
 *
 */
class Application_Admin
{
	/**
	 * @return string
	 */
	public static function getBaseId(): string
	{
		return 'admin';
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
		Logger::setLogger( new Logger_Admin() );
		Auth::setController( new Auth_Controller_Admin() );

		SysConf_Jet_UI::setViewsDir( $router->getBase()->getViewsPath() . 'ui/' );
		SysConf_Jet_Form::setDefaultViewsDir( $router->getBase()->getViewsPath() . 'form/' );
		SysConf_Jet_ErrorPages::setErrorPagesDir( $router->getBase()->getPagesDataPath( $router->getLocale() ) );
	}

	/**
	 * @param string $dialog_id
	 * @param array $options
	 *
	 * @return null|string
	 */
	public static function requireDialog( string $dialog_id, array $options = [] ): null|string
	{

		$page = MVC::getPage( 'dialog-' . $dialog_id );

		if(
			!$page ||
			!$page->getContent()
		) {
			return null;
		}

		$content = $page->getContent()[0];

		$module = $content->getModuleInstance();

		if( !$module ) {
			return null;
		}

		$view = Factory_MVC::getViewInstance( $module->getViewsDir() . 'dialog-hooks/' );
		foreach( $options as $k => $v ) {
			$view->setVar( $k, $v );
		}

		return $view->render( $dialog_id );

	}
}