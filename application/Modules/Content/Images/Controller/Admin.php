<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\AJAX;
use Jet\Mvc_Controller_Default;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;
use Jet\UI;
use Jet\UI_messages;
use Jet\UI_searchForm;

use JetApplicationModule\UI\Admin\Main as UI_module;

/**
 *
 */
class Controller_Admin extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'default' => Main::ACTION_GET_GALLERY,

		'view'    => Main::ACTION_GET_GALLERY,
		'edit'    => Main::ACTION_UPDATE_GALLERY,
		'delete'  => Main::ACTION_DELETE_GALLERY,
		'add'     => Main::ACTION_ADD_GALLERY,

		'uploadImage'  => Main::ACTION_ADD_IMAGE,
		'deleteImages' => Main::ACTION_DELETE_IMAGE

	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 * @var Controller_Admin_Router
	 */
	protected $router;


	/**
	 *
	 * @return Controller_Admin_Router
	 */
	public function getControllerRouter()
	{
		if( !$this->router ) {
			$this->router = new Controller_Admin_Router( $this );
		}

		return $this->router;
	}


	/**
	 * @param string  $current_label
	 * @param Gallery $gallery
	 */
	protected function _setBreadcrumbNavigation( $gallery = null, $current_label = '' )
	{
		UI_module::initBreadcrumb();

		if( $gallery ) {

			foreach( $gallery->getPath() as $gallery ) {
				/**
				 * @var Gallery $gallery
				 */
				Navigation_Breadcrumb::addURL(
						$gallery->getTitle(),
						$this->getControllerRouter()->getEditOrViewURI( $gallery->getId() )
				);
			}
		}

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 * @return UI_searchForm;
	 */
	public function _initGalleries() {
		$search_form = UI::searchForm( 'images' );

		$this->view->setVar( 'search_form', $search_form );

		if($search_form->getValue()) {
			$this->view->setVar( 'galleries', Gallery::search($search_form->getValue()) );

		} else {
			$this->view->setVar( 'galleries', Gallery::getTree() );
		}

		return $search_form;
	}

	/**
	 *
	 */
	public function default_Action()
	{

		$this->_setBreadcrumbNavigation( $this->getParameter( 'gallery' ) );

		$this->_initGalleries();

		$this->view->setVar( 'selected_id', '' );


		$this->render( 'admin/default' );
	}

	/**
	 *
	 */
	public function add_Action()
	{


		$parent_id = $this->getParameter( 'parent_id' );

		$this->_setBreadcrumbNavigation( Gallery::get( $parent_id ), Tr::_( 'Create a new gallery' ) );

		$this->_initGalleries();


		$gallery = new Gallery();
		$gallery->setParentId( $parent_id );

		$edit_form = $gallery->getAddForm();

		if( $gallery->catchAddForm() ) {
			$gallery->save();

			$this->logAllowedAction( 'Gallery created', $gallery->getId(), $gallery->getTitle(), $gallery );

			UI_messages::success(
				Tr::_( 'Gallery <b>%TITLE%</b> has been created', [ 'TITLE' => $gallery->getTitle() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $parent_id );

		$this->render( 'admin/add' );

	}

	/**
	 *
	 */
	public function edit_Action()
	{
		$this->_setBreadcrumbNavigation( $this->getParameter( 'gallery' ) );

		$this->_initGalleries();

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );

		$edit_form = $gallery->getEditForm();

		if( $gallery->catchEditForm() ) {
			$gallery->save();

			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			UI_messages::success(
				Tr::_( 'Gallery <b>%TITLE%</b> has been updated', [ 'TITLE' => $gallery->getTitle() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'upload_form', $gallery->getImageUploadForm() );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );

		$this->render( 'admin/edit' );
	}


	/**
	 *
	 */
	public function view_Action()
	{
		$this->_setBreadcrumbNavigation( $this->getParameter( 'gallery' ) );
		$this->_initGalleries();

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );

		$edit_form = $gallery->getEditForm();
		$edit_form->setIsReadonly();

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );

		$this->render( 'admin/edit' );

	}

	/**
	 *
	 */
	public function delete_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );

		$parent_id = $gallery->getParentId();

		$gallery->delete();

		$this->logAllowedAction( 'Gallery deleted', $gallery->getId(), $gallery->getTitle(), $gallery );

		UI_messages::warning(
			Tr::_( 'Gallery <b>%TITLE%</b> has been deleted', [ 'TITLE' => $gallery->getTitle() ] )
		);

		Http_Headers::movedTemporary( $this->getControllerRouter()->getEditOrViewURI( $parent_id ) );
	}


	/**
	 *
	 */
	public function uploadImage_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );

		$upload_form = $gallery->getImageUploadForm();


		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'upload_form', $upload_form );

		$ok = false;

		if( ( $images = $gallery->catchImageUploadForm() ) ) {

			$ids = [];
			$names = [];
			foreach( $images as $i ) {
				$ids[] = $i->getIdObject()->toString();
				$names[] = $i->getFileName();
			}

			$upload_form->setCommonMessage( UI_messages::createSuccess( Tr::_('Image %FILE_NAME% uploaded ....', ['FILE_NAME'=>implode(', ', $names)]) ) );

			$this->logAllowedAction(
				'image_uploaded',
				implode(', ', $ids),
				implode(', ', $names)
			);

			$ok = true;
		} else {
			$upload_form->setCommonMessage( UI_messages::createDanger( $upload_form->getField('file')->getLastErrorMessage() ) );
		}


		AJAX::formResponse(
			$ok,
			[
				'images_area' => $this->view->render('admin/parts/images'),
				'upload_form_area' => $this->view->render('admin/parts/upload-form')
			]);

	}


	/**
	 *
	 */
	public function deleteImages_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );


		$POST = Http_Request::POST();

		if( $POST->exists( 'images' ) ) {

			foreach( $POST->getRaw( 'images' ) as $image_id ) {
				$image = Gallery_Image::get( $image_id );
				if( $image ) {
					$image->delete();

					$this->logAllowedAction(
						'Image deleted', $image->getIdObject()->toString(), $image->getFileName(), $image
					);
				}
			}
			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}
	}
}