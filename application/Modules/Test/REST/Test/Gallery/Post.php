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
class Test_Gallery_Post extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Add (POST) - valid';
	}

	/**
	 *
	 */
	public function test(): void
	{

		$data = [
			'parent_id' => '',
			'localized' =>
				[
				]
		];

		foreach( Content_Gallery::getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title' => 'test title (' . $locale->getLanguageName( $locale ) . ') ' . time(),
			];
		}

		$this->client->post( 'gallery', $data );

	}
}
