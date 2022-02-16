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
class Test_Article_DeleteInvalid extends Test_Abstract
{

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Delete - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$id = 'unknown-unknown';

		$this->client->delete( 'article/' . $id );

	}
}
