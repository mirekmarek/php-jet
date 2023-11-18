<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Web\UI;

use Jet\MVC_Controller_Default;
use Jet\MVC;
use JetApplication\Application_Web_Pages;


/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{


	/**
	 *
	 */
	public function homepage_Action(): void
	{
		$this->output( 'homepage' );
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
		$secret_area_page = Application_Web_Pages::secretArea();
		if($secret_area_page) {
			$this->view->setVar( 'page_tree_current', [$secret_area_page] );
			
			$this->output( 'secret-area-menu' );
		}
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