<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

use JetApplication\Application_Web;


/**
 *
 */
class Test_Article_PutInvalid extends Test_Abstract
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
		return 'Update (PUT) - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test()
	{
		$id = $this->data['articles'][0]['id'];

		$data = [
			'date_time' => 'xxxx',
			'localized' =>
				[
				]
		];

		foreach(Application_Web::getSite()->getLocales() as $locale_str=>$locale ) {
			$data['localized'][$locale_str] = [
				'title' => '',
				'annotation' => '',
				'text' => '',
			];
		}

		$this->client->put('article/'.$id, $data);

	}
}
