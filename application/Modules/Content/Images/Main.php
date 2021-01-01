<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'images';

	const ACTION_GET_GALLERY = 'get_gallery';
	const ACTION_ADD_GALLERY = 'add_gallery';
	const ACTION_UPDATE_GALLERY = 'update_gallery';
	const ACTION_DELETE_GALLERY = 'delete_gallery';

	const ACTION_ADD_IMAGE = 'add_image';
	const ACTION_UPDATE_IMAGE = 'update_image';
	const ACTION_DELETE_IMAGE = 'delete_image';
	
}