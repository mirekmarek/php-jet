<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\Content\Images;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ADMIN_MAIN_PAGE = 'images';
	
	public const ACTION_GET_GALLERY = 'get_gallery';
	public const ACTION_ADD_GALLERY = 'add_gallery';
	public const ACTION_UPDATE_GALLERY = 'update_gallery';
	public const ACTION_DELETE_GALLERY = 'delete_gallery';
	
	public const ACTION_ADD_IMAGE = 'add_image';
	public const ACTION_UPDATE_IMAGE = 'update_image';
	public const ACTION_DELETE_IMAGE = 'delete_image';

}