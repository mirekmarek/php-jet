<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;


/**
 *
 */
class Test_Gallery_GetImagesUnknown extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Get images - unknown gallery (error simulation)';
	}

	/**
	 *
	 */
	public function test() : void
	{

		$this->client->get('gallery/unknownunknown/image');
	}
}
