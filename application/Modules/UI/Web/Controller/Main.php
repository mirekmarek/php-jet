<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var Main
	 */
	protected $module = null;

	/**
	 *
	 * @param  Mvc_Page_Content_Interface $content
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
	public function logout_Action()
	{
		Auth::logout();

		Http_Headers::movedTemporary( Mvc_Page::get( Mvc_Page::HOMEPAGE_ID )->getURL() );
	}


	/**
	 *
	 */
	public function main_menu_Action()
	{

		$this->view->setVar( 'site_tree_current', [ Mvc::getCurrentSite()->getHomepage( Mvc::getCurrentLocale() ) ] );

		$this->render( 'main-menu' );
	}

	/**
	 *
	 */
	public function secret_area_menu_Action()
	{
		$this->view->setVar( 'site_tree_current', [ Mvc_Page::get( 'secret_area' ) ] );

		$this->render( 'secret-area-menu' );
	}

	/**
	 *
	 */
	public function breadcrumbNavigation_Action()
	{
		$view = $this->getParameter( 'view', 'default' );

		$this->render( 'breadcrumb-navigation/'.$view );

	}
}