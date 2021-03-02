<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Web;

use Jet\Mvc_Controller_Default;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Mvc_Page_Content_Interface;
use Jet\Http_Request;
use Jet\Auth;
use Jet\Http_Headers;


/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		parent::__construct( $content );

		$GET = Http_Request::GET();

		if( $GET->exists( 'logout' ) ) {
			$this->logout_Action();
		}
	}


	/**
	 *
	 */
	public function logout_Action(): void
	{
		Auth::logout();

		Http_Headers::movedTemporary( Mvc_Page::get( Mvc_Page::HOMEPAGE_ID )->getURL() );
	}


	/**
	 *
	 */
	public function main_menu_Action(): void
	{

		$this->view->setVar( 'site_tree_current', [Mvc::getCurrentSite()->getHomepage( Mvc::getCurrentLocale() )] );

		$this->output( 'main-menu' );
	}

	/**
	 *
	 */
	public function secret_area_menu_Action(): void
	{
		$this->view->setVar( 'site_tree_current', [Mvc_Page::get( 'secret_area' )] );

		$this->output( 'secret-area-menu' );
	}

	/**
	 *
	 */
	public function breadcrumbNavigation_Action(): void
	{
		$view = $this->getParameter( 'view', 'default' );

		$this->output( 'breadcrumb-navigation/' . $view );

	}
}