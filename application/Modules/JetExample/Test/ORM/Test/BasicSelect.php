<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\ORM;

/**
 *
 */
class Test_BasicSelect extends Test_Abstract
{
	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Basic SELECT';
	}

	/**
	 *
	 */
	public function test()
	{

		$q = Model_A1::createQuery();

		echo $q->setSelect(['id', 'text']);

	}
}