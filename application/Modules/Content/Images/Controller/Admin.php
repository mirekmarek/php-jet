<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\AJAX;
use Jet\Mvc_Controller_Default;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Controller_Router_AddEditDelete;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;
use Jet\UI_messages;
use Jet\Locale;
use Jet\IO_File;

use JetApplicationModule\UI\Admin\Main as UI_module;

/**
 *
 */
class Controller_Admin extends Mvc_Controller_Default
{
	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 * @var Mvc_Controller_Router_AddEditDelete
	 */
	protected $router;

	/**
	 * @var Gallery
	 */
	protected $gallery;


	/**
	 *
	 * @return Mvc_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter()
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router_AddEditDelete(
				$this,
				function($id) {
					return (bool)($this->gallery = Gallery::get($id));
				},
				[
					'listing'=> Main::ACTION_GET_GALLERY,
					'view'   => Main::ACTION_GET_GALLERY,
					'add'    => Main::ACTION_ADD_GALLERY,
					'edit'   => Main::ACTION_UPDATE_GALLERY,
					'delete' => Main::ACTION_DELETE_GALLERY,
				]
			);

			$GET = Http_Request::GET();
			$id = $GET->getString('id');
			$action = $GET->getString('action');

			$this->router->getAction('add')
				->setResolver(function() use ($action, $id) {
					if($action!='add') {
						return false;
					}

					$this->gallery = Gallery::get($id);

					return true;
				})
				->setURICreator( function($id) {
					return Http_Request::currentURI(['action'=>'add', 'id'=>$id]);
				} );

			$this->router->addAction('image_upload')
				->setResolver(function() use ($id, $action) {
					return (
						$action=='image_upload' &&
						($this->gallery=Gallery::get($id))
					);
				})
				->setURICreator( function( $gallery_id ) {
					return Http_Request::currentURI(['action'=>'image_upload', 'id'=>$gallery_id]);
				});

			$this->router->addAction('image_delete')
				->setResolver(function() use ($id, $action) {
					return (
						$action=='image_delete' &&
						($this->gallery=Gallery::get($id))
					);
				})
				->setURICreator( function( $gallery_id ) {
					return Http_Request::currentURI(['action'=>'image_delete', 'id'=>$gallery_id]);
				});

		}

		return $this->router;
	}


	/**
	 * @param Gallery|null $gallery
	 * @param string  $current_label
	 */
	protected function _setBreadcrumbNavigation( $gallery = null, $current_label = '' )
	{
		UI_module::initBreadcrumb();

		if( $gallery ) {

			foreach( $gallery->getPath() as $gallery ) {
				Navigation_Breadcrumb::addURL(
						$gallery->getTitle(),
						$this->getControllerRouter()->action('edit')->URI( $gallery->getId() )
				);
			}
		}

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function _initGalleries() {
		/*
		$search_form = UI::searchForm( 'images' );

		$this->view->setVar( 'search_form', $search_form );
		*/

		/*
		if($search_form->getValue()) {
			$this->view->setVar( 'galleries', Gallery::search($search_form->getValue()) );

		} else {
		*/
			$this->view->setVar( 'galleries', Gallery::getTree() );
		//}

		//return $search_form;
	}

	/**
	 *
	 */
	public function listing_Action()
	{

		$this->_setBreadcrumbNavigation( $this->gallery );

		$this->_initGalleries();

		$this->view->setVar( 'selected_id', '' );


		$this->render( 'admin/default' );
	}

	/**
	 *
	 */
	public function add_Action()
	{

		$parent_id = $this->gallery ? $this->gallery->getId() : '';

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

			Http_Headers::reload( ['id'=>$gallery->getId()], ['action'] );
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
		$gallery = $this->gallery;

		$this->_setBreadcrumbNavigation( $gallery );

		$this->_initGalleries();

		$edit_form = $gallery->getEditForm();

		if( $gallery->catchEditForm() ) {
			$gallery->save();

			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			UI_messages::success(
				Tr::_( 'Gallery <b>%TITLE%</b> has been updated', [ 'TITLE' => $gallery->getTitle() ] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'upload_form', $gallery->getImageUploadForm() );
		$this->view->setVar( 'selected_id', $gallery->getId() );

		$this->render( 'admin/edit' );
	}


	/**
	 *
	 */
	public function view_Action()
	{
		$gallery = $this->gallery;

		$this->_setBreadcrumbNavigation( $gallery );
		$this->_initGalleries();

		$edit_form = $gallery->getEditForm();
		$edit_form->setIsReadonly();

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getId() );

		$this->render( 'admin/edit' );

	}

	/**
	 *
	 */
	public function delete_Action()
	{
		$gallery = $this->gallery;

		$parent_id = $gallery->getParentId();

		$gallery->delete();

		$this->logAllowedAction( 'Gallery deleted', $gallery->getId(), $gallery->getTitle(), $gallery );

		UI_messages::warning(
			Tr::_( 'Gallery <b>%TITLE%</b> has been deleted', [ 'TITLE' => $gallery->getTitle() ] )
		);

		Http_Headers::reload( ['id'=>$parent_id], ['action'] );
	}


	/**
	 *
	 */
	public function image_upload_Action()
	{
		$gallery = $this->gallery;

		$upload_form = $gallery->getImageUploadForm();


		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'upload_form', $upload_form );

		$ok = false;

		if( ( $images = $gallery->catchImageUploadForm() ) ) {

			$ids = [];
			$names = [];
			foreach( $images as $i ) {
				$ids[] = $i->getId();
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
			if(Http_Request::postMaxSizeExceeded()) {
				$error_message = 'You are uploading too large files<br/>'
								.'<br/>'
								.'The maximum size of one uploaded file is: <b>%max_upload_size%</b><br/>'
								.'The maximum number of uploaded files is: <b>%max_file_uploads%</b><br/>';

				$upload_form->setCommonMessage( UI_messages::createDanger( Tr::_($error_message, [
					'max_upload_size' => Locale::getCurrentLocale()->formatSize(IO_File::getMaxUploadSize()),
					'max_file_uploads' => Locale::getCurrentLocale()->formatInt(IO_File::getMaxFileUploads())
				]) ) );
			}
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
	public function image_delete_Action()
	{
		$gallery = $this->gallery;


		$POST = Http_Request::POST();

		if( $POST->exists( 'images' ) ) {

			foreach( $POST->getRaw( 'images' ) as $image_id ) {
				$image = Gallery_Image::get( $image_id );
				if( $image ) {
					$image->delete();

					$this->logAllowedAction(
						'Image deleted', $image->getId(), $image->getFileName(), $image
					);
				}
			}
			Http_Headers::reload( ['id'=>$gallery->getId()], ['action'] );
		}
	}
}