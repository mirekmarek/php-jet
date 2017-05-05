<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use JetExampleApp\Mvc_Page;

use JetUI\UI;
use JetUI\breadcrumbNavigation;

use Jet\Form;
use Jet\Mvc_Controller_Standard;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Form_Field_FileImage;
use Jet\Tr;


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
	protected $module_instance = null;

	/**
	 *
	 */
	public function default_Action()
	{

		$this->_setBreadcrumbNavigation( $this->getActionParameterValue( 'gallery' ) );

		$this->view->setVar( 'selected_id', Gallery::ROOT_ID );

		$this->view->setVar( 'galleries', Gallery::getTree() );

		$this->render( 'default' );
	}

	/**
	 * @param string  $current_label
	 * @param Gallery $gallery
	 */
	protected function _setBreadcrumbNavigation( $gallery = null, $current_label = '' )
	{
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc_Page::get( Main::ADMIN_MAIN_PAGE );

		breadcrumbNavigation::addItem(
			UI::icon( $page->getIcon() ).'&nbsp;&nbsp;'.$page->getBreadcrumbTitle(), $page->getURL()
		);

		if( $gallery ) {

			$path = [];
			$tree = Gallery::getTree();

			$parent = $tree->getNode( $gallery->getId() );
			//var_dump($parent);die();

			//while($parent)
			do {
				$path[static::getControllerRouter()->getEditOrViewURI( $parent->getId() )] = $parent->getLabel();

				$parent = $parent->getParent();
			} while( $parent&&!$parent->getIsRoot() );

			$path = array_reverse( $path );

			foreach( $path as $url => $title ) {
				breadcrumbNavigation::addItem( $title, $url );
			}
		}

		if( $current_label ) {
			breadcrumbNavigation::addItem( $current_label );
		}
	}

	/**
	 *
	 * @return Controller_Admin_Main_Router
	 */
	public function getControllerRouter()
	{
		return $this->module_instance->getAdminControllerRouter();
	}

	/**
	 *
	 */
	public function add_Action()
	{


		$parent_id = $this->getActionParameterValue( 'parent_id' );

		$this->_setBreadcrumbNavigation( Gallery::get( $parent_id ), Tr::_( 'Create a new gallery' ) );


		$gallery = new Gallery();
		$gallery->setParentId( $parent_id );

		$edit_form = $gallery->getCommonForm();

		if( $gallery->catchForm( $edit_form ) ) {
			$this->logAllowedAction( 'Gallery created', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar( 'has_access', true );
		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $parent_id );
		$this->view->setVar( 'galleries', Gallery::getTree() );

		$this->render( 'default' );

	}

	/**
	 *
	 */
	public function edit_Action()
	{
		$this->_setBreadcrumbNavigation( $this->getActionParameterValue( 'gallery' ) );

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getActionParameterValue( 'gallery' );

		$edit_form = $gallery->getCommonForm();

		if( $gallery->catchForm( $edit_form ) ) {
			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}


		$this->handleUploadImage( $gallery );
		$this->handleDeleteImages( $gallery );


		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );
		$this->view->setVar( 'galleries', Gallery::getTree() );

		$this->render( 'default' );

	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleUploadImage( Gallery $gallery )
	{
		if( !$this->module_instance->checkAclCanDoAction( 'add_image' ) ) {
			return;
		}

		$upload_form = $this->getUploadForm( $gallery );

		if( $upload_form->catchValues() ) {
			if( ( $image = $gallery->catchUploadForm( $upload_form ) ) ) {
				$this->logAllowedAction(
					'Image created', $image->getIdObject()->toString(), $image->getFileName(), $image
				);

				$image->getThumbnail(
					Config::getDefaultThbMaxW(), Config::getDefaultThbMaxH()
				);

				Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
			}

		}
		$this->view->setVar( 'upload_form', $upload_form );
	}

	/**
	 * @param Gallery $gallery
	 *
	 * @return Form
	 */
	protected function getUploadForm( Gallery $gallery )
	{
		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField( 'file' );

		$image_field->setMaximalSize(
			Config::getDefaultMaxW(), Config::getDefaultMaxH()
		);

		return $upload_form;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleDeleteImages( Gallery $gallery )
	{
		if( !$this->module_instance->checkAclCanDoAction( 'delete_image' ) ) {
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

	/**
	 *
	 */
	public function view_Action()
	{
		$this->_setBreadcrumbNavigation( $this->getActionParameterValue( 'gallery' ) );

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getActionParameterValue( 'gallery' );

		$edit_form = $gallery->getCommonForm();
		$edit_form->setIsReadonly();

		$this->view->setVar( 'gallery', $gallery );
		$this->view->setVar( 'edit_form', $edit_form );
		$this->view->setVar( 'selected_id', $gallery->getIdObject() );
		$this->view->setVar( 'galleries', Gallery::getTree() );

		$this->render( 'default' );

	}
}