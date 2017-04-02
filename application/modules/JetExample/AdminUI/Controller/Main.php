<?php
/**
 *
 * Default admin UI module
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet\Mvc_Controller_Standard;
use Jet\Application_Modules;
use JetExampleApp\Application_Modules_Module_Manifest;
use Jet\Auth;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Http_Headers;
use JetUI\menu;

class Controller_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
        'logout' => false,
		'default' => false,
		'breadcrumb_navigation' => false,
		'messages' => false,
		'main_menu' => false,
	];

	protected static $_menu_initialized = false;

	/**
	 *
	 */
	public function initialize() {
		if(static::$_menu_initialized) {
			return;
		}

		static::$_menu_initialized = true;

		menu::addMenu('content', 'Content', 1);
		menu::addMenu('system', 'System', 3);

		foreach( Application_Modules::getActivatedModulesList() as $manifest ) {
			/**
			 * @var Application_Modules_Module_Manifest $manifest
			 */
			foreach( $manifest->getMenuItems() as $menu_item ) {

				if(!$menu_item->getAccessAllowed()) {
					continue;
				}

				$menu = menu::getMenu( $menu_item->getParentMenuId() );
				$menu->addMenuItem( $menu_item );
			}

		}

	}

    /**
     *
     */
    public function logout_Action() {
        Auth::logout();

        Http_Headers::movedTemporary( Mvc_Page::get('admin')->getURL() );
    }

    /**
     *
     */
    public function default_Action() {
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -1 );

		$this->render('default');

	}

	/**
	 *
	 */
	public function breadcrumb_navigation_Action() {
		$this->render( 'breadcrumb_navigation' );
	}

	/**
	 *
	 */
	public function messages_Action() {
		$this->render( 'messages' );
	}

	/**
	 *
	 */
	public function main_menu_Action() {
		$this->render('main_menu');
	}

}