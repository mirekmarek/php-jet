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
class Test_Gallery_GetListSortAndPagination extends Test_Abstract
{

	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Get list (sort and pagination)';
	}

	/**
	 *
	 */
	public function test() : void
	{
		$this->client->get('gallery', ['sort'=>'-title', 'items_per_page'=>1, 'page'=>2 ]);
	}
}
