<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Web;

use Jet\MVC_Controller_Default;
use Jet\MVC;
use Jet\MVC_Page_Content_Interface;
use Jet\Http_Request;
use Jet\Auth;
use Jet\Http_Headers;


/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 * @param MVC_Page_Content_Interface $content
	 */
	public function __construct( MVC_Page_Content_Interface $content )
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

		Http_Headers::movedTemporary( MVC::getHomePage()->getURL() );
	}


	/**
	 *
	 */
	public function main_menu_Action(): void
	{

		$this->view->setVar( 'page_tree_current', [MVC::getBase()->getHomepage( MVC::getLocale() )] );

		$this->output( 'main-menu' );
	}

	/**
	 *
	 */
	public function secret_area_menu_Action(): void
	{
		$this->view->setVar( 'page_tree_current', [MVC::getPage( 'secret_area' )] );

		$this->output( 'secret-area-menu' );
	}

	/**
	 *
	 */
	public function breadcrumbNavigation_Action(): void
	{
		$view = $this->content->getParameter( 'view', 'default' );

		$this->output( 'breadcrumb-navigation/' . $view );

	}
}