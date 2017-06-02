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

//TODO: testovaci moduly rozdělit na TestREST, TestORM, TestMVC, TestFORMS
/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'test_forms'       => false,
		'test_mvc_info'    => false,
	    'test_rest'        => false
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 *
	 */
	public function test_forms_Action()
	{

		$obj = new DataModelTest_FormGenerator();

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

	/**
	 *
	 */
	public function test_rest_Action()
	{
		//TODO:
	}

}