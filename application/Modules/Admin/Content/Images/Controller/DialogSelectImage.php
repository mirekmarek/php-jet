<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\Content\Images;

/**
 *
 */
class Controller_DialogSelectImage extends Controller_Main
{
	
	public function _initGalleries(): string
	{
		$this->view->setVar('select_image_mode', true);
		
		return parent::_initGalleries();
	}
	
}