<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected function _getTitle(): string
	{
		return 'Get list (sort and pagination)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$this->client->get( 'gallery', ['sort'           => '-title',
		                                'items_per_page' => 1,
		                                'page'           => 2
		] );
	}
}
