<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

use Jet\Form;
use Jet\Mvc_Controller_Default;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Form_Field_FileImage;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;
use Jet\UI;
use Jet\UI_messages;
use Jet\UI_searchForm;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

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