<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;
use Jet\Data_DateTime;
use Jet\Mvc_Site;


/**
 *
 */
class Test_Gallery_GetImageThb extends Test_Abstract
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
		return 'Get image thumbnail - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$image = $this->data['images'][0];

		$this->client->get('gallery/'.$image['gallery_id'].'/image/'.$image['id'], [ 'thumbnail'=>'40x30' ]);

	}
}
