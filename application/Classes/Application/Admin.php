<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Logger;
use Jet\Mvc_Site;
use Jet\Mvc_Page;
use Jet\Mvc_View;
use Jet\Mvc_Router;
use Jet\Auth;

/**
 *
 */
class Application_Admin
{
	/**
	 * @return string
	 */
	public static function getSiteId() : string
	{
		return 'admin';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getSite() : Mvc_Site
	{
		return Mvc_Site::get( static::getSiteId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router ) : void
	{
		Application::initErrorPages( $router );
		Application_Logger::setLogger( new Application_Logger_Admin() );
		Auth::setController( new Auth_Controller_Admin() );

	}

	/**
	 * @param string $dialog_id
	 * @param array  $options
	 *
	 * @return null|string
	 */
	public static function requireDialog( string $dialog_id, array $options=[] ) : null|string
	{

		$page = Mvc_Page::get('dialog-'.$dialog_id);

		if(
			!$page ||
			!$page->getContent()
		) {
			return null;
		}

		$content = $page->getContent()[0];

		$module = $content->getModuleInstance();

		if(!$module) {
			return null;
		}

		$view = new Mvc_View( $module->getViewsDir().'admin/dialog-hooks/' );
		foreach( $options as $k=>$v ) {
			$view->setVar( $k, $v );
		}

		return $view->render( $dialog_id );

	}
}