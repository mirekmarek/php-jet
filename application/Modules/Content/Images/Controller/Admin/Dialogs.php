<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\Mvc_Controller_Default;

/**
 *
 */
class Controller_Admin_Dialogs extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'select_image' => Main::ACTION_GET_GALLERY,


	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;


	/**
	 *
	 */
	public function select_image_Action()
	{

	}


}