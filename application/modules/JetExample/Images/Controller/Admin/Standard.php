<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Images
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

class Controller_Admin_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_gallery'
	);


	public function default_Action() {

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		$GET = Jet\Http_Request::GET();
		$POST = Jet\Http_Request::POST();

		$gallery = false;

		if( $GET->exists('ID') ) {

			$gallery = Gallery::get( $GET->getString('ID') );
			if($gallery) {
				$this->view->setVar('selected_ID', $gallery->getID());

			}
		}

		if($GET->exists('new')) {

			$parent_ID = $gallery ? $gallery->getID() : '_root_';

			$gallery = new Gallery();
			$gallery->setParentID( $parent_ID );
		}



		if($gallery) {
			$this->view->setVar('gallery', $gallery );

			$edit_form = $gallery->getCommonForm();

			if($gallery->catchForm($edit_form)) {
				//TODO: check ACL

				$gallery->validateProperties();
				$gallery->save();

				Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());

			}

			$this->view->setVar('edit_form', $edit_form);

			if(!$gallery->getIsNew()) {
				if(
					$GET->exists('delete_images') &&
					$POST->exists('images')
				) {
					//TODO: check ACL

					foreach( $POST->getRaw('images') as $image_ID ) {
						$image = Gallery_Image::get( $image_ID );
						if($image) {
							$image->delete();
						}
					}
					Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());
				}

				$upload_form = $gallery->getUploadForm();

				if($upload_form->catchValues()) {
					//TODO: check ACL

					if($gallery->catchUploadForm( $upload_form )) {
						Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());
					}

				}

				$this->view->setVar('upload_form', $upload_form );

			}
		}

		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');
	}
}