<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;
use Jet\Application_Modules;


/**
 *
 */
class Test_Gallery_ImagePost extends Test_Abstract
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
		return 'Upload image (POST) - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$gallery = $this->data['galleries'][0];
		$id = $gallery['id'];

		$dir = Application_Modules::getModuleDir('Test.REST').'data/';

		$valid_image = $dir.'test_valid.jpg';

		$this->client->post('gallery/'.$id.'/image', [], $valid_image);


	}
}
