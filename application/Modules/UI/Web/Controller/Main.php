<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Web;

use Jet\MVC_Controller_Default;
use Jet\MVC;
use Jet\Translator;


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
		Translator::setCurrentDictionary( Translator::getCurrentDictionary().'.homepage' );
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