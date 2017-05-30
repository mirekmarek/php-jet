<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Form;
use Jet\Mvc_Controller_Standard;
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
class Controller_Admin_Main extends Mvc_Controller_Standard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => Main::ACTION_GET_GALLERY, 'view' => Main::ACTION_GET_GALLERY,
		'edit'    => Main::ACTION_UPDATE_GALLERY, 'add' => Main::ACTION_ADD_GALLERY,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 * @var Controller_Admin_Main_Router
	 */
	protected $router;


	/**
	 *
	 * @return Controller_Admin_Main_Router
	 */
	public function getControllerRouter()
	{
		if( !$this->router ) {
			$this->router = new Controller_Admin_Main_Router( $this );
		}

		return $this->router;
	}


	/**
	 * @param string  $current_label
	 * @param Gallery $gallery
	 */
	protected function _setBreadcrumbNavigation( $gallery = null, $current_label = '' )
	{
		AdminUI_module::initBreadcrumb();

		if( $gallery ) {

			$path = [];
			$tree = Gallery::getTree();

			$parent = $tree->getNode( $gallery->getId() );



			do {
				$path[static::getControllerRouter()->getEditOrViewURI( $parent->getId() )] = $parent->getLabel();

				$parent = $parent->getParent();
			} while( $parent && !$parent->getIsRoot() );

			$path = array_reverse( $path );

			foreach( $path as $url => $title ) {
				Navigation_Breadcrumb::addURL( $title, $url );
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

		$this->view->setVar( 'galleries', Gallery::getTree() );

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


		$this->render( 'default' );
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

		$edit_form = $gallery->getCommonForm();

		if( $gallery->catchForm( $edit_form ) ) {
			$this->logAllowedAction( 'Gallery created', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $parent_id );

		$this->render( 'add' );

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

		$edit_form = $gallery->getCommonForm();

		if( $gallery->catchForm( $edit_form ) ) {
			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );

		$this->handleUploadImage( $gallery );
		$this->handleDeleteImages( $gallery );



		$this->render( 'edit' );
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

		$edit_form = $gallery->getCommonForm();
		$edit_form->setIsReadonly();

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );

		$this->render( 'edit' );

	}


	/**
	 * @param Gallery $gallery
	 */
	public function handleUploadImage( Gallery $gallery )
	{
		if( !$this->module->checkAccess( 'add_image' ) ) {
			return;
		}

		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField( 'file' );

		$image_field->setMaximalSize(
			Config::getDefaultMaxW(),
			Config::getDefaultMaxH()
		);


		$this->view->setVar( 'upload_form', $upload_form );

		if( $upload_form->catchInput() ) {
			$ok = false;
			if( ( $image = $gallery->catchUploadForm( $upload_form ) ) ) {

				$upload_form->setCommonMessage( UI_messages::createSuccess( Tr::_('Image %FILE_NAME% uploaded ....', ['FILE_NAME'=>$image->getFileName()]) ) );

				$this->logAllowedAction(
					'image_uploaded',
					$image->getIdObject()->toString(),
					$image->getFileName(),
					$image
				);
				$ok = true;
			} else {
				$upload_form->setCommonMessage( UI_messages::createDanger( $upload_form->getField('file')->getLastErrorMessage() ) );
			}

			$this->ajaxFormResponse($upload_form, $ok, [
				'images_area' => $this->view->render('parts/images'),
			    'upload_form_area' => $this->view->render('parts/upload-form')
			]);
		}
	}


	/**
	 * @param Gallery $gallery
	 */
	public function handleDeleteImages( Gallery $gallery )
	{
		if( !$this->module->checkAccess( 'delete_image' ) ) {
			return;
		}

		$this->view->setVar( 'can_delete_image', true );

		$POST = Http_Request::POST();

		if( $POST->exists( 'images' ) ) {

			foreach( $POST->getRaw( 'images' ) as $image_id ) {
				$image = Gallery_Image::get( $image_id );
				if( $image ) {
					$this->logAllowedAction(
						'Image deleted', $image->getIdObject()->toString(), $image->getFileName(), $image
					);
					$image->delete();
				}
			}
			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}
	}
}