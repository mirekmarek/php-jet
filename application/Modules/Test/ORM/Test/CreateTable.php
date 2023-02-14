<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Helper;

/**
 *
 */
class Test_CreateTable extends Test_Abstract
{
	public function getId(): string
	{
		return 'CreateTable';
	}
	
	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'CREATE TABLE';
	}

	/**
	 *
	 */
	public function test() : void
	{
		echo DataModel_Helper::getCreateCommand( Model_A1::class );
	}
}