<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AdminUI;

use Jet\Mvc_Controller_Standard;

use Jet\Auth;
use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Http_Request;

use JetExampleApp\Mvc_Page;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'logout'    => false, 'default' => false, 'breadcrumb_navigation' => false, 'messages' => false,
		'main_menu' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * Controller_Main constructor.
	 *
	 * @param Main $module_instance
	 */
	public function __construct( Main $module_instance )
	{
		parent::__construct( $module_instance );

		Main::getMenuItems();

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
	public function default_Action()
	{
		Mvc::getCurrentPage()->breadcrumbNavigationShift( -1 );

		$this->render( 'default' );

	}

	/**
	 *
	 */
	public function breadcrumb_navigation_Action()
	{
		$this->render( 'breadcrumb_navigation' );
	}

	/**
	 *
	 */
	public function messages_Action()
	{
		$this->render( 'messages' );
	}

	/**
	 *
	 */
	public function main_menu_Action()
	{
		$this->render( 'main_menu' );
	}

}