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
class Test_Gallery_GetImages extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled() : bool
	{
		return count($this->data['galleries'])>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Get images';
	}

	/**
	 *
	 */
	public function test() : void
	{

		$ids = [];
		foreach( $this->data['galleries'] as $item ) {
			$ids[] = $item['id'];
		}

		shuffle($ids);
		$id = $ids[0];

		$this->client->get('gallery/'.$id.'/image');
	}
}
