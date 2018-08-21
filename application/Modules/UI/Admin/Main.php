<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\UI\Admin;

use Jet\Application_Module;
use Jet\Navigation_Menu;
use Jet\Navigation_Breadcrumb;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\UI;


/**
 *
 */
class Main extends Application_Module
{

	/**
	 * @var bool
	 */
	protected static $menu_init_done;

	/**
	 *
	 */
	public static function initBreadcrumb()
	{
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc::getCurrentPage();

		Navigation_Breadcrumb::reset();

		Navigation_Breadcrumb::addURL(
			UI::icon( $page->getIcon() ).'&nbsp;&nbsp;'.$page->getBreadcrumbTitle(),
			$page->getURL()
		);

	}

	/**
	 *
	 */
	public static function initMenuItems()
	{
		if( static::$menu_init_done ) {
			return;
		}

		Navigation_Menu::initMenu( 'admin' );

		static::$menu_init_done = true;

	}

}