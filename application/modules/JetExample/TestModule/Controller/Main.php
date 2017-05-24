<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\TestModule;

use Jet\Mvc_Controller_Standard;
use Jet\Mvc;
use Jet\Mvc_Page;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard
{
	protected static $ACL_actions_check_map = [
		'main_menu'        => false,
		'secret_area_menu' => false,
		'test_forms'       => false,
		'test_mvc_info'    => false,
	];

	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

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
	public function test_forms_Action()
	{

		$obj = new TestDM1();

		$form = $obj->getCommonForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$this->view->setVar( 'form_data', $form->getValues() );
		}
		$this->view->setVar( 'form', $form );


		$this->render( 'test-forms' );
	}

	/**
	 *
	 */
	public function test_mvc_info_Action()
	{
		$this->render( 'test-mvc-info' );
	}

}