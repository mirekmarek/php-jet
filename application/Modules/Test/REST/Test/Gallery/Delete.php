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
class Test_Gallery_Delete extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->data['galleries'])>0;
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
		$id = $this->data['galleries'][0]['id'];

		$this->client->delete('gallery/'.$id);

	}
}
