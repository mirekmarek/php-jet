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
class Test_Gallery_DeleteImageInvalid extends Test_Abstract
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
		return 'Delete image - unknown (error simulation)';
	}

	/**
	 *
	 */
	public function test()
	{
		$image = $this->data['images'][0];

		$this->client->delete('gallery/'.$image['gallery_id'].'/image/unknownunknown');

	}
}