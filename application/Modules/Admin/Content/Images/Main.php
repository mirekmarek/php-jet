<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\Content\Images;

use Jet\Application_Module;
use Jet\Factory_MVC;
use Jet\Form_Field;
use Jet\Translator;
use JetApplication\Application_Admin_Services_ImageManager;

/**
 *
 */
class Main extends Application_Module implements Application_Admin_Services_ImageManager
{
	public const ADMIN_MAIN_PAGE = 'images';
	
	public const ACTION_GET_GALLERY = 'get_gallery';
	public const ACTION_ADD_GALLERY = 'add_gallery';
	public const ACTION_UPDATE_GALLERY = 'update_gallery';
	public const ACTION_DELETE_GALLERY = 'delete_gallery';
	
	public const ACTION_ADD_IMAGE = 'add_image';
	public const ACTION_UPDATE_IMAGE = 'update_image';
	public const ACTION_DELETE_IMAGE = 'delete_image';
	
	public function includeSelectImageDialog(): string
	{
		
		return Translator::setCurrentDictionaryTemporary(
			$this->module_manifest->getName(),
			function() {
				$view = Factory_MVC::getViewInstance( $this->getViewsDir() );
				return $view->render('dialog/select-image/hook');
			}
		);
		
	}
	
	public function renderSelectImageWidget( Form_Field $form_field ) : string
	{
		$view = Factory_MVC::getViewInstance( $this->getViewsDir() );
		$view->setVar('form_field', $form_field);
		
		return Translator::setCurrentDictionaryTemporary(
			$this->module_manifest->getName(),
			function() use ($view, &$res) {
				return $view->render('widget/select-image');
			}
		);
	}
}