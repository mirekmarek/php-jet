<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

/**
 *
 */
class Test_Gallery_GetImage extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->data['images'])>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Get image - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$image = $this->data['images'][0];

		$this->client->get('gallery/'.$image['gallery_id'].'/image/'.$image['id']);

	}
}
