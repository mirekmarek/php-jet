<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public function isEnabled(): bool
	{
		return count( $this->data['articles'] ) > 0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Delete - valid';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$id = $this->data['articles'][0]['id'];

		$this->client->delete( 'article/' . $id );

	}
}
