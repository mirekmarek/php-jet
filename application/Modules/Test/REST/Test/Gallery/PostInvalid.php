<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

use JetApplication\Application_Web;


/**
 *
 */
class Test_Gallery_PostInvalid extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Add (POST) - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test()
	{
		$data = [
			'parent_id' => 'xxxxx',
			'localized' =>
				[
				]
		];

		foreach( Application_Web::getSite()->getLocales() as $locale_str=>$locale ) {
			$data['localized'][$locale_str] = [
				'title' => ''
			];
		}

		$this->client->post('gallery', $data);

	}
}
