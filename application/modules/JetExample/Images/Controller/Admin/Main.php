<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Form;
use Jet\Mvc_Controller_Standard;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Form_Field_FileImage;
use JetUI\UI;
use JetUI\breadcrumbNavigation;
use Jet\Tr;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

class Controller_Admin_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => 'get_gallery',
		'view' => 'get_gallery',
		'edit' => 'update_gallery',
		'add' => 'add_gallery',
	];

	/**
	 * @param string $current_label
	 * @param Gallery $gallery
	 */
	protected function _setBreadcrumbNavigation( $gallery=null, $current_label='' ) {
		$menu_item = AdminUI_module::getMenuItems()['content/images'];

		breadcrumbNavigation::addItem(
			UI::icon($menu_item->getIcon()).'&nbsp;&nbsp;'. $menu_item->getLabel(),
			$menu_item->getUrl()
		);

		if($gallery) {

			$path = [];
			$tree = Gallery::getTree();

			$parent = $tree->getNode($gallery->getId());
			//var_dump($parent);die();

			//while($parent)
			do {
				$path[static::getControllerRouter()->getEditOrViewURI( $parent->getId() )] = $parent->getLabel();

				$parent = $parent->getParent();
			} while( $parent && !$parent->getIsRoot() );

			$path = array_reverse($path);

			foreach( $path as $url=>$title ) {
				breadcrumbNavigation::addItem( $title, $url );
			}
		}

		if($current_label) {
			breadcrumbNavigation::addItem( $current_label );
		}
	}

	/**
	 *
	 * @return Controller_Admin_Main_Router
	 */
	public function getControllerRouter() {
		return $this->module_instance->getAdminControllerRouter();
	}

	/**
	 *
	 */
	public function default_Action() {

		$this->_setBreadcrumbNavigation( $this->getActionParameterValue('gallery') );

		$this->view->setVar('selected_id', Gallery::ROOT_ID);

		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('default');
	}

	/**
     *
	 */
	public function add_Action() {


        $parent_id = $this->getActionParameterValue('parent_id');

		$this->_setBreadcrumbNavigation( Gallery::get($parent_id), Tr::_('Create a new gallery') );


		$gallery = new Gallery();
		$gallery->setParentId( $parent_id );

		$edit_form = $gallery->getCommonForm();

		if($gallery->catchForm($edit_form)) {

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}

		$this->view->setVar('has_access', true);
		$this->view->setVar('gallery', $gallery );
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_id', $parent_id );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('default');

	}

	/**
     *
	 */
	public function edit_Action() {
		$this->_setBreadcrumbNavigation( $this->getActionParameterValue('gallery'));

        /**
         * @var Gallery $gallery
         */
        $gallery = $this->getActionParameterValue('gallery');

		$edit_form = $gallery->getCommonForm();

		if($gallery->catchForm($edit_form)) {

			$gallery->save();

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}



		$this->handleUploadImage( $gallery );
		$this->handleDeleteImages( $gallery );


		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_id', $gallery->getIdObject() );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('default');

	}


	/**
     *
	 */
	public function view_Action() {
		$this->_setBreadcrumbNavigation( $this->getActionParameterValue('gallery'));

        /**
         * @var Gallery $gallery
         */
        $gallery = $this->getActionParameterValue('gallery');

		$edit_form = $gallery->getCommonForm();
		$edit_form->setIsReadonly();

		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_id', $gallery->getIdObject() );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('default');

	}

	/**
	 * @param Gallery $gallery
	 * @return Form
	 */
	protected function getUploadForm( Gallery $gallery ) {
		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField('file');

		$image_field->setMaximalSize(
            Config::getDefaultMaxW(),
            Config::getDefaultMaxH()
		);

		return $upload_form;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleUploadImage( Gallery $gallery ) {
		if(!$this->module_instance->checkAclCanDoAction('add_image')) {
			return;
		}

		$upload_form = $this->getUploadForm( $gallery );

		if($upload_form->catchValues()) {
			if( ($image=$gallery->catchUploadForm( $upload_form )) ) {

				$image->getThumbnail(
                    Config::getDefaultThbMaxW(),
                    Config::getDefaultThbMaxH()
				);

				Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
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

		$this->view->setVar('can_delete_image', true);

		$POST = Http_Request::POST();

		if( $POST->exists('images') ) {

			foreach( $POST->getRaw('images') as $image_id ) {
				$image = Gallery_Image::get( $image_id );
				if($image) {
					$image->delete();
				}
			}
			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $gallery->getId() ) );
		}
	}
}