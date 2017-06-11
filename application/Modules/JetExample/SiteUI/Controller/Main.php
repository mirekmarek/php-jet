<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\SiteUI;

use Jet\Mvc_Controller_Default;
use Jet\Mvc;
use Jet\Mvc_Page;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	protected static $ACL_actions_check_map = [
		'main_menu'            => false,
		'secret_area_menu'     => false,
	    'breadcrumbNavigation' => false,
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;

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