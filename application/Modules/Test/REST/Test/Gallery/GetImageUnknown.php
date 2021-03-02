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
class Test_Gallery_GetImageUnknown extends Test_Abstract
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
		return 'Get image - unknown (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$image = $this->data['images'][0];

		$this->client->get( 'gallery/' . $image['gallery_id'] . '/image/unknownunknown' );

	}
}
