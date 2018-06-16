<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;
use Jet\Data_DateTime;

use JetApplication\Application;
use JetApplication\Application_Web;


/**
 *
 */
class Test_Article_Put extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->data['articles'])>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Update (PUT) - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$id = $this->data['articles'][0]['id'];

		$data = [
			'date_time' => Data_DateTime::now()->toString(),
			'localized' =>
				[
				]
		];

		foreach(Application_Web::getSite()->getLocales() as $locale_str=>$locale) {
			$data['localized'][$locale_str] = [
				'title' => 'test title '.time(),
				'annotation' => 'annotation annotation '.time(),
				'text' => 'text text text '.time(),
			];
		}

		$this->client->put('article/'.$id, $data);

	}
}
