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
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_gallery'
	);


	public function default_Action() {

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		$GET = Jet\Http_Request::GET();

		$this->view->setVar('selected_ID', '_root_');

		if($GET->exists('new')) {
			$this->handleAdd();
		} else {
			if( $GET->exists('ID') ) {

				$gallery = Gallery::get( $GET->getString('ID') );
				if($gallery) {
					if( $GET->exists('delete_images') ) {
						$this->handleDeleteImages( $gallery );
					}

					$this->handleEdit( $gallery );
					$this->handleUploadImage( $gallery );
					$this->view->setVar('selected_ID', $gallery->getID());
				}
			}

		}

		$this->view->setVar('can_add_gallery', $this->module_instance->checkAclCanDoAction('add_gallery'));
		$this->view->setVar('can_get_image', $this->module_instance->checkAclCanDoAction('get_image'));
		$this->view->setVar('can_delete_image', $this->module_instance->checkAclCanDoAction('delete_image'));



		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');
	}

	/**
	 *
	 */
	public function handleAdd() {
		if( !$this->module_instance->checkAclCanDoAction('add_gallery') ) {
			return;
		}


		$GET = Jet\Http_Request::GET();

		$parent_gallery = null;
		if( $GET->exists('ID') ) {
			$parent_gallery = Gallery::get( $GET->getString('ID') );
			if(!$parent_gallery) {
				return;
			}
		}

		$parent_ID = $parent_gallery ? $parent_gallery->getID() : '_root_';

		$gallery = new Gallery();
		$gallery->setParentID( $parent_ID );

		$edit_form = $gallery->getCommonForm();

		if($gallery->catchForm($edit_form)) {

			$gallery->validateProperties();
			$gallery->save();

			Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());

		}

		$this->view->setVar('selected_ID', $parent_ID );
		$this->view->setVar('gallery', $gallery );
		$this->view->setVar('has_access', true);
		$this->view->setVar('edit_form', $edit_form);
	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleEdit( Gallery $gallery ) {

		$this->view->setVar('gallery', $gallery );

		$has_access = $this->module_instance->checkAclCanDoAction( 'update_gallery' );

		$edit_form = $gallery->getCommonForm();

		if($has_access) {
			if($gallery->catchForm($edit_form)) {

				$gallery->validateProperties();
				$gallery->save();

				Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());

			}

		}

		$this->view->setVar('has_access', $has_access);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('edit_form', $edit_form);



	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleUploadImage( Gallery $gallery ) {
		if(!$this->module_instance->checkAclCanDoAction('add_image')) {
			return;
		}

		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Jet\Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField('file');
		/**
		 * @var Config $config
		 */
		$config = $this->module_instance->getConfig();


		$image_field->setMaximalSize(
			$config->getDefaultMaxW(),
			$config->getDefaultMaxH()
		);


		if($upload_form->catchValues()) {
			if( ($image=$gallery->catchUploadForm( $upload_form )) ) {

				$image->getThumbnail(
					$config->getDefaultThbMaxW(),
					$config->getDefaultThbMaxH()
				);

				Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());
			}

		}

		$this->view->setVar('upload_form', $upload_form );

	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleDeleteImages( Gallery $gallery ) {
		if(!$this->module_instance->checkAclCanDoAction('delete_image')) {
			return;
		}

		$POST = Jet\Http_Request::POST();

		if( $POST->exists('images') ) {

			foreach( $POST->getRaw('images') as $image_ID ) {
				$image = Gallery_Image::get( $image_ID );
				if($image) {
					$image->delete();
				}
			}
			Jet\Http_Headers::movedTemporary('?ID='.$gallery->getID());
		}
	}
}