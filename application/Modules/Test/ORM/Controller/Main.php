<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Backend_MySQL;
use Jet\DataModel_Backend_MySQL_Config;
use Jet\DataModel_Backend_SQLite;
use Jet\DataModel_Backend_SQLite_Config;
use Jet\MVC_Controller_Default;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function test_orm_Action(): void
	{
		$backends = [];

		$backends['MySQL'] = new DataModel_Backend_MySQL( (new DataModel_Backend_MySQL_Config()) );
		$backends['SQLite'] = new DataModel_Backend_SQLite( (new DataModel_Backend_SQLite_Config()) );

		$_tests = [
			'BasicSelect',
			'BasicSelectWhere',
			'SimpleInternalRelation',
			'SimpleInternalSubRelation',

			'CountSelect',

			'ExternalRelation'
		];

		$tests = [];
		foreach( $_tests as $test ) {

			$class_name = __NAMESPACE__ . '\\Test_' . $test;
			$tests[$test] = new $class_name( $test );
		}

		$this->view->setVar( 'backends', $backends );
		$this->view->setVar( 'tests', $tests );


		$this->output( 'tests' );
	}
}