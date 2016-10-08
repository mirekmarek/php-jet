<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Form;
use Jet\Mvc;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_MicroRouter;
use Jet\Mvc_Router_Abstract;
use Jet\Mvc_Page_Content_Interface;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Form_Field_FileImage;

class Controller_Admin_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var Mvc_MicroRouter
	 */
	protected $micro_router;

    /**
     * @var Mvc_MicroRouter
     */
    protected $_standard_admin_micro_router;

	protected static $ACL_actions_check_map = [
		'default' => 'get_gallery',
		'view' => 'get_gallery',
		'edit' => 'update_gallery',
		'add' => 'add_gallery',
	];

	/**
	 *
	 */
	public function initialize() {
		Mvc::checkCurrentContentIsDynamic();
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -2 );
		$this->micro_router = $this->getMicroRouter();
		$this->view->setVar( 'router', $this->micro_router );
	}

    /**
     * @param Mvc_Router_Abstract $router
     *
     * @return Mvc_MicroRouter
     */
    public function getMicroRouter( Mvc_Router_Abstract $router=null ) {
        if($this->_standard_admin_micro_router) {
            return $this->_standard_admin_micro_router;
        }

        if(!$router) {
            $router = Mvc::getCurrentRouter();
        }

        $router = new Mvc_MicroRouter( $router, $this->module_instance );

        $base_URI = Mvc::getCurrentPageURI();

        $gallery_validator = function( &$parameters ) {
            $gallery = Gallery::get( $parameters[0] );
            if(!$gallery) {
                return false;
            }

            $parameters['gallery'] = $gallery;
            return true;

        };

        $router->addAction('add', '/^add:([\S]+)$/', 'add_gallery', true)
            ->setCreateURICallback( function( $parent_ID ) use($base_URI) { return $base_URI.'add:'.rawurlencode($parent_ID).'/'; } )
            ->setParametersValidatorCallback( function(&$parameters) use ($gallery_validator) {

                $parameters['parent_ID'] = $parameters[0];

                if($parameters[0]==Gallery::ROOT_ID) {
                    return true;
                }

                $gallery = Gallery::get( $parameters[0] );
                if(!$gallery) {
                    unset($parameters['parent_ID']);
                    return false;
                }

                return true;
            } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_gallery', true)
            ->setCreateURICallback( function( $gallery_ID ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($gallery_ID).'/'; } )
            ->setParametersValidatorCallback( $gallery_validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_gallery', true)
            ->setCreateURICallback( function( $gallery_ID ) use($base_URI) { return $base_URI.'view:'.rawurlencode($gallery_ID).'/'; } )
            ->setParametersValidatorCallback( $gallery_validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_gallery', true)
            ->setCreateURICallback( function( $gallery_ID ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($gallery_ID).'/'; } )
            ->setParametersValidatorCallback( $gallery_validator );

        $this->_standard_admin_micro_router = $router;

        return $router;
    }


    /**
     * @param Mvc_Page_Content_Interface $page_content
     * @return bool
     */
    public function parseRequestURL_Admin( Mvc_Page_Content_Interface $page_content=null ) {
        $router = $this->getMicroRouter( Mvc::getCurrentRouter() );

        return $router->resolve( $page_content );
    }


	/**
	 *
	 */
	public function default_Action() {

		$this->view->setVar('selected_ID', Gallery::ROOT_ID);

		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');
	}

	/**
     *
	 */
	public function add_Action() {

        $parent_ID = $this->getActionParameterValue('parent_ID');

		$gallery = new Gallery();
		$gallery->setParentID( $parent_ID );

		$edit_form = $gallery->getCommonForm();

		if($gallery->catchForm($edit_form)) {

			$gallery->save();

			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $gallery->getIdObject() ) );
		}

		$this->view->setVar('has_access', true);
		$this->view->setVar('gallery', $gallery );
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_ID', $parent_ID );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');

	}

	/**
     *
	 */
	public function edit_Action() {

        /**
         * @var Gallery $gallery
         */
        $gallery = $this->getActionParameterValue('gallery');

		$edit_form = $gallery->getCommonForm();

		if($gallery->catchForm($edit_form)) {

			$gallery->save();

			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $gallery->getIdObject() ) );
		}



		$this->handleUploadImage( $gallery );
		$this->handleDeleteImages( $gallery );


		$this->view->setVar('has_access', true);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_ID', $gallery->getIdObject() );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');

	}


	/**
     *
	 */
	public function view_Action() {

        /**
         * @var Gallery $gallery
         */
        $gallery = $this->getActionParameterValue('gallery');

		$edit_form = $gallery->getCommonForm();

		$this->view->setVar('has_access', false);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('edit_form', $edit_form);
		$this->view->setVar('selected_ID', $gallery->getIdObject() );
		$this->view->setVar('galleries', Gallery::getTree() );

		$this->render('classic/default');

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
		/**
		 * @var Config $config
		 */
		$config = $this->module_instance->getConfig();


		$image_field->setMaximalSize(
			$config->getDefaultMaxW(),
			$config->getDefaultMaxH()
		);

		return $upload_form;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleUploadImage( Gallery $gallery ) {
		if(!$this->module_instance->checkAclCanDoAction('add_image', null, false)) {
			return;
		}

		$upload_form = $this->getUploadForm( $gallery );

		if($upload_form->catchValues()) {
			if( ($image=$gallery->catchUploadForm( $upload_form )) ) {

				/**
				 * @var Config $config
				 */
				$config = $this->module_instance->getConfig();

				$image->getThumbnail(
					$config->getDefaultThbMaxW(),
					$config->getDefaultThbMaxH()
				);

				Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $gallery->getIdObject() ) );
			}

		}
		$this->view->setVar('upload_form', $upload_form );
	}

	/**
	 * @param Gallery $gallery
	 */
	public function handleDeleteImages( Gallery $gallery ) {
		if(!$this->module_instance->checkAclCanDoAction('delete_image', null, false)) {
			return;
		}

		$this->view->setVar('can_delete_image', true);

		$POST = Http_Request::POST();

		if( $POST->exists('images') ) {

			foreach( $POST->getRaw('images') as $image_ID ) {
				$image = Gallery_Image::get( $image_ID );
				if($image) {
					$image->delete();
				}
			}
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $gallery->getIdObject() ) );
		}
	}
}