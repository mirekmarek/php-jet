<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

use JetApplication\Application_Web;


/**
 *
 */
class Test_Gallery_Put extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled() : bool
	{
		return count($this->data['galleries'])>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Update (PUT) - valid';
	}

	/**
	 *
	 */
	public function test() : void
	{
		$gallery = $this->data['galleries'][0];
		$id = $gallery['id'];

		$data = [
			'parent_id' => $gallery['parent_id'],
			'localized' =>
				[
				]
		];

		foreach( Application_Web::getSite()->getLocales() as $locale_str=>$locale ) {
			$data['localized'][$locale_str] = [
				'title' => 'test title '.time()
			];
		}

		$this->client->put('gallery/'.$id, $data);

	}
}
