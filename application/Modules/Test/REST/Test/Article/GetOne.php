<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;


/**
 *
 */
class Test_Article_GetOne extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->data['articles'])>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Get item';
	}

	/**
	 *
	 */
	public function test()
	{

		$ids = [];
		foreach( $this->data['articles'] as $item ) {
			$ids[] = $item['id'];
		}

		shuffle($ids);
		$id = $ids[0];

		$this->client->get('article/'.$id);
	}
}
