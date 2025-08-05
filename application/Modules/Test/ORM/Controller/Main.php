<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\Config;
use Jet\DataModel_Backend;
use Jet\Factory_DataModel;
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
		
		Config::setBeTolerant(true);
		foreach( DataModel_Backend::getAllBackendTypes() as $type=>$type_label ) {
			$config = Factory_DataModel::getBackendConfigInstance( $type );
			$backend = Factory_DataModel::getBackendInstance( $type, $config );
			if($backend->isAvailable()) {
				$backends[$type] = $backend;
			}
		}
		
		$_tests = [
			Test_CreateTable::class,
			
			Test_BasicSelect::class,
			Test_BasicSelectWhere::class,
			Test_SimpleInternalRelation::class,
			Test_SimpleInternalSubRelation::class,

			Test_CountSelect::class,

			Test_ExternalRelation::class
		];

		$tests = [];
		foreach( $_tests as $class_name ) {
			/**
			 * @var Test_Abstract $test
			 * @phpstan-ignore varTag.nativeType
			 */
			$test = new $class_name();
			
			$tests[$test->getId()] = $test;
		}

		$this->view->setVar( 'backends', $backends );
		$this->view->setVar( 'tests', $tests );


		$this->output( 'tests' );
	}
}