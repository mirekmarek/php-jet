<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use JetApplicationModule\Content\ImageGallery\Entity\Gallery;


/**
 *
 */
class Test_Gallery_PostInvalid extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Add (POST) - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$data = [
			'parent_id' => 'xxxxx',
			'localized' =>
				[
				]
		];

		foreach( Gallery::getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title' => ''
			];
		}

		$this->client->post( 'gallery', $data );

	}
}
