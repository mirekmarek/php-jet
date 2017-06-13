<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;
use Jet\Data_DateTime;
use Jet\Mvc_Site;


/**
 *
 */
class Test_Gallery_Post extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Add (POST) - valid';
	}

	/**
	 *
	 */
	public function test()
	{

		$data = [
			'parent_id' => '',
			'localized' =>
				[
				]
		];

		foreach(Mvc_Site::getAllLocalesList(false) as $locale_str=>$locale) {
			$data['localized'][$locale_str] = [
				'title' => 'test title ('.$locale->getLanguageName($locale).') '.time(),
			];
		}

		$this->client->post('gallery', $data);

	}
}
