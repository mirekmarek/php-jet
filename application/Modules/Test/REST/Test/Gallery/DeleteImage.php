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
class Test_Gallery_DeleteImage extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return count( $this->data['images'] ) > 0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Delete image - valid';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$image = $this->data['images'][0];

		$this->client->delete( 'gallery/' . $image['gallery_id'] . '/image/' . $image['id'] );

	}
}
