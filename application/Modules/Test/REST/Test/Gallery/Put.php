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
class Test_Gallery_Put extends Test_Abstract
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
		return 'Update (PUT) - valid';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$gallery = $this->data['galleries'][0];
		$id = $gallery['id'];

		$data = [
			'parent_id' => $gallery['parent_id'],
			'localized' =>
				[
				]
		];

		foreach( Content_Gallery::getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title' => 'test title ' . time()
			];
		}

		$this->client->put( 'gallery/' . $id, $data );

	}
}
