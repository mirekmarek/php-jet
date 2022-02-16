<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Admin;

use Jet\MVC_Controller_Default;

use Jet\Auth;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\MVC_Page_Content_Interface;

use Jet\MVC;

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
	public function default_Action(): void
	{
		$this->output( 'default' );
	}

	/**
	 *
	 */
	public function breadcrumb_navigation_Action(): void
	{
		$this->output( 'breadcrumb_navigation' );
	}

	/**
	 *
	 */
	public function messages_Action(): void
	{
		$this->output( 'messages' );
	}

	/**
	 *
	 */
	public function main_menu_Action(): void
	{
		$this->output( 'main_menu' );
	}

}