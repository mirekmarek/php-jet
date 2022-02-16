<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use JetApplication\Content_Gallery;


/**
 *
 */
class Test_Gallery_PutInvalid extends Test_Abstract
{
	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return count( $this->data['galleries'] ) > 0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Update (PUT) - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$id = $this->data['galleries'][0]['id'];

		$data = [
			'parent_id' => 'xxxx',
			'localized' =>
				[
				]
		];

		foreach( Content_Gallery::getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title' => ''
			];
		}

		$this->client->put( 'gallery/' . $id, $data );

	}
}
