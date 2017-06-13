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
class Test_Gallery_Put extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->data['galleries'])>0;
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
		$gallery = $this->data['galleries'][0];
		$id = $gallery['id'];

		$data = [
			'parent_id' => $gallery['parent_id'],
			'localized' =>
				[
				]
		];

		foreach(Mvc_Site::getAllLocalesList() as $locale=>$locale_name) {
			$data['localized'][$locale] = [
				'title' => 'test title '.time()
			];
		}

		$this->client->put('gallery/'.$id, $data);

	}
}
