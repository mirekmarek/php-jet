<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc;
use Jet\Mvc_Controller_REST;
use Jet\Form_Field_FileImage;

class Controller_REST extends Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	const ERR_CODE_NO_FILE = 'NoFile';
	const ERR_CODE_IMAGE_ALLREADY_EXISTS = 'ImageAllreadyExists';
	const ERR_CODE_UNKNOWN_ERROR = 'UnknownError';

	protected static $ACL_actions_check_map = [
		'get_image' => 'get_image',
		'get_image_thumbnail' => false,
		'post_image' => 'add_image',
		'delete_image' => 'delete_image',
		'put_copy_image' => 'add_image',

		'get_gallery' => 'get_gallery',
		'get_gallery_tree' => 'get_gallery',
		'get_gallery_tree_lazy' => 'get_gallery',
		'post_gallery' => 'add_gallery',
		'put_gallery' => 'update_gallery',
		'delete_gallery' => 'delete_gallery'
	];

	protected static $errors = [
		self::ERR_CODE_AUTHORIZATION_REQUIRED => [Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Authorization required! '],
		self::ERR_CODE_ACCESS_DENIED => [Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Insufficient permissions! '],
		self::ERR_CODE_UNSUPPORTED_DATA_CONTENT_TYPE => [Http_Headers::CODE_400_BAD_REQUEST, 'Unsupported data Content-Type'],
		self::ERR_CODE_FORM_ERRORS => [Http_Headers::CODE_400_BAD_REQUEST, 'There are errors in form'],
		self::ERR_CODE_UNKNOWN_ITEM => [Http_Headers::CODE_404_NOT_FOUND, 'Unknown item'],

		self::ERR_CODE_NO_FILE => [Http_Headers::CODE_406_NOT_ACCEPTABLE, 'No file sent'],
		self::ERR_CODE_IMAGE_ALLREADY_EXISTS => [Http_Headers::CODE_409_CONFLICT, 'Image allready uploaded'],
		self::ERR_CODE_UNKNOWN_ERROR => [Http_Headers::CODE_400_BAD_REQUEST, 'Unknown error'],
	];

	/**
	 *
	 */
	public function initialize() {
	}

	/**
	 * @param null|int $id
	 */
	public function get_image_Action( $id=null ) {
		if($id) {
			$image = $this->_getImage($id);
			$this->responseData($image);
		} else {
			$gallery_id = Http_Request::GET()->getString('gallery_id');


			$thumbnail_max_size_w = Http_Request::GET()->getInt('thumbnail_max_size_w', Config::getDefaultThbMaxW() );
			$thumbnail_max_size_h = Http_Request::GET()->getInt('thumbnail_max_size_h', Config::getDefaultThbMaxH() );

			$list = Gallery_Image::getListAsData( $gallery_id );
			if($thumbnail_max_size_w>0 && $thumbnail_max_size_h>0) {
				$list->setArrayWalkCallback(
					function( &$image_data )
					use ($thumbnail_max_size_w, $thumbnail_max_size_h)
					{
						$image = Gallery_Image::get($image_data['id']);

						$image_data['thumbnail_URI'] = $image->getThumbnail($thumbnail_max_size_w, $thumbnail_max_size_h)->getURI();
					}
				);
			}

			$this->responseDataModelsList( $list );

		}
	}

	/**
	 * @param string $gallery_id
	 */
	public function post_image_Action( $gallery_id ) {
		$gallery = $this->_getGallery($gallery_id);

		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField('file');

		$image_field->setMaximalSize(
            Config::getDefaultMaxW(),
            Config::getDefaultMaxH()
		);

		if( ($image=$gallery->catchUploadForm( $upload_form, true )) ) {

			$image->getThumbnail(
                Config::getDefaultThbMaxW(),
                Config::getDefaultThbMaxH()
			);

			$this->responseOK();
		} else {
			$this->responseFormErrors( $upload_form->getAllErrors() );
		}


	}

	/**
	 * @param string $image_id
	 */
	public function put_copy_image_Action( $image_id ) {
		$image = $this->_getImage( $image_id );
		$data = $this->getRequestData();
		$gallery = $this->_getGallery( $data['target_gallery_id'] );

        if(
            (
                !isset($data['overwrite_if_exists']) ||
                !$data['overwrite_if_exists']
            )
            &&
            $gallery->getImageExists($image->getFileName())
        ) {
            $this->responseOK();
        }

		$image = $gallery->addImage( $image->getFilePath(), $image->getFileName(), true );

		$image->getThumbnail(
            Config::getDefaultThbMaxW(),
            Config::getDefaultThbMaxH()
		);

		$this->responseOK();
	}

	/**
	 * @param string $image_id
	 */
	public function delete_image_Action( $image_id ) {
		$image = $this->_getImage($image_id);

		$image->delete();
		$this->responseOK();
	}

	/**
	 * @param string $image_id
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 */
	public function get_image_thumbnail_Action( $image_id, $maximal_size_w, $maximal_size_h ) {
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		if(!$maximal_size_w || !$maximal_size_h) {
			$this->responseUnknownItem($image_id);
		}

		$image = $this->_getImage($image_id);

		$URI = $image->getThumbnail($maximal_size_w, $maximal_size_h)->getURI();

		Http_Headers::movedPermanently( $URI );
	}


	/**
	 * @param null|int $id
	 */
	public function get_gallery_Action( $id=null ) {
		if($id) {
			$gallery = $this->_getGallery($id);
			$this->responseData($gallery);
		} else {
			$this->responseData( Gallery::getTree() );
		}
	}

	/**
	 * @param string $parent_id (optional)
	 */
	public function get_gallery_tree_lazy_action( $parent_id="" ) {
		$tree = Gallery::getTree();

		if($parent_id) {
			$node = $tree->getNode( $parent_id );
			if(!$node) {
				$this->responseUnknownItem( $parent_id );
			}

			$tree->setRootNode($node);

		}
		$tree->setLazyMode(true);

		$this->responseData( $tree );

	}


	/**
	 *
	 */
	public function get_gallery_tree_action() {
		$tree = Gallery::getTree();

		$this->responseData( $tree );

	}

	/**
	 *
	 */
	public function post_gallery_Action() {
		$gallery = Gallery::getNew();

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->save();
			Mvc::truncateRouterCache();

			$this->responseData($gallery);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	/**
	 * @param $id
	 */
	public function put_gallery_Action( $id ) {
		$gallery = $this->_getGallery($id);

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->save();

			Mvc::truncateRouterCache();

			$this->responseData($gallery);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	/**
	 * @param string $id
	 */
	public function delete_gallery_Action( $id ) {
		$gallery = $this->_getGallery($id);

		$gallery->delete();
		Mvc::truncateRouterCache();

		$this->responseOK();

	}

	/**
	 * @param $id
	 * @return Gallery
	 */
	protected  function _getGallery($id) {
		$gallery = Gallery::get($id);

		if(!$gallery) {
			$this->responseUnknownItem($id);
		}

		return $gallery;
	}

	/**
	 * @param $id
	 * @return Gallery_Image
	 */
	protected  function _getImage($id) {
		$gallery = Gallery_Image::get($id);

		if(!$gallery) {
			$this->responseUnknownItem($id);
		}

		return $gallery;
	}

}