<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Application_Modules;


/**
 *
 */
class Test_Gallery_ImagePostInvalidType extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return count( $this->data['galleries'] ) > 0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Upload image (POST) - invalid file type (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$gallery = $this->data['galleries'][0];
		$id = $gallery['id'];

		$dir = Application_Modules::getModuleDir( 'Test.REST' ) . 'data/';

		$valid_image = $dir . 'test_invalid.txt';

		$this->client->post( 'gallery/' . $id . '/image', [], $valid_image );


	}
}
