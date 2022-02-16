<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Admin;

use Jet\Application_Module;
use Jet\Navigation_Breadcrumb;
use Jet\MVC;
use Jet\MVC_Page;
use Jet\UI;


/**
 *
 */
class Main extends Application_Module
{

	/**
	 *
	 */
	public static function initBreadcrumb()
	{
		/**
		 * @var MVC_Page $page
		 */
		$page = MVC::getPage();

		Navigation_Breadcrumb::reset();

		Navigation_Breadcrumb::addURL(
			UI::icon( $page->getIcon() ) . '&nbsp;&nbsp;' . $page->getBreadcrumbTitle(),
			$page->getURL()
		);

	}
}