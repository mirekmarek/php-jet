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
class Test_Article_PostInvalid extends Test_Abstract
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
			'date_time' => 'xxxxx',
			'localized' =>
				[
				]
		];

		foreach(Mvc_Site::getAllLocalesList() as $locale=>$locale_name) {
			$data['localized'][$locale] = [
				'title' => '',
				'annotation' => '',
				'text' => '',
			];
		}

		$this->client->post('article', $data);

	}
}
