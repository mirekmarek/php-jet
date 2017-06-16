<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Test\ORM;

use Jet\DataModel_Backend;
use Jet\DataModel_Backend_MySQL;
use Jet\DataModel_Backend_MySQL_Config;
use Jet\DataModel_Backend_SQLite;
use Jet\DataModel_Backend_SQLite_Config;
use Jet\Mvc_Controller_Default;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
	    'test_orm'        => false
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;


	/**
	 *
	 */
	public function test_orm_Action()
	{
		$backends = [];

		$backends['MySQL'] = new DataModel_Backend_MySQL( (new DataModel_Backend_MySQL_Config( true )) );
		$backends['SQLite'] = new DataModel_Backend_SQLite( (new DataModel_Backend_SQLite_Config( true )) );

		$_tests = [
			'BasicSelect',
		    'BasicSelectWhere',
		    'SimpleInternalRelation',
			'SimpleInternalSubRelation',

			//TODO:
		];

		$tests = [];
		foreach( $_tests as $test ) {

			$class_name = __NAMESPACE__.'\\Test_'.$test;
			$tests[$test] = new $class_name( $test );
		}

		$this->view->setVar('backends', $backends);
		$this->view->setVar('tests', $tests);



		$this->render('tests');

	}

}