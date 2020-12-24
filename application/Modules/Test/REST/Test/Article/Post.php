<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;
use Jet\Data_DateTime;

use JetApplication\Application_Web;


/**
 *
 */
class Test_Article_Post extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Add (POST) - valid';
	}

	/**
	 *
	 */
	public function test() : void
	{

		$data = [
			'date_time' => Data_DateTime::now()->toString(),
			'localized' =>
				[
				]
		];

		foreach(Application_Web::getSite()->getLocales() as $locale_str=>$locale) {
			$data['localized'][$locale_str] = [
				'title' => 'test title ('.$locale->getLanguageName($locale).') '.time(),
				'annotation' => 'annotation annotation',
				'text' => 'text text text',
			];
		}

		$this->client->post('article', $data);

	}
}
