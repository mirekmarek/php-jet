<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

/**
 *
 */
class Test_BasicSelect extends Test_Abstract
{
	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Basic SELECT';
	}

	/**
	 *
	 */
	public function test() : void
	{

		$q = Model_A1::createQuery();

		echo $q->setSelect( [
			'id',
			'text'
		] );

	}
}