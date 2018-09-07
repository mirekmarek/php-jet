<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;


/**
 *
 */
class Test_Article_Delete extends Test_Abstract
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
		return 'Delete - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$id = $this->data['articles'][0]['id'];

		$this->client->delete('article/'.$id);

	}
}
