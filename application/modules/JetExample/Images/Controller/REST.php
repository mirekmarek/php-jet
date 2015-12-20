<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;
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
	 * @param null|int $ID
	 */
	public function get_image_Action( $ID=null ) {
		if($ID) {
			$image = $this->_getImage($ID);
			$this->responseData($image);
		} else {
			$gallery_ID = Http_Request::GET()->getString('gallery_ID');

			/**
			 * @var Config $config
			 */
			$config = $this->module_instance->getConfig();

			$thumbnail_max_size_w = Http_Request::GET()->getInt('thumbnail_max_size_w', $config->getDefaultThbMaxW() );
			$thumbnail_max_size_h = Http_Request::GET()->getInt('thumbnail_max_size_h', $config->getDefaultThbMaxH() );

			$list = Gallery_Image::getListAsData( $gallery_ID );
			if($thumbnail_max_size_w>0 && $thumbnail_max_size_h>0) {
				$list->setArrayWalkCallback(
					function( &$image_data )
					use ($thumbnail_max_size_w, $thumbnail_max_size_h)
					{
						$image = Gallery_Image::get($image_data['ID']);

						$image_data['thumbnail_URI'] = $image->getThumbnail($thumbnail_max_size_w, $thumbnail_max_size_h)->getURI();
					}
				);
			}

			$this->responseDataModelsList( $list );

		}
	}

	/**
	 * @param string $gallery_ID
	 */
	public function post_image_Action( $gallery_ID ) {
		$gallery = $this->_getGallery($gallery_ID);

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

		if( ($image=$gallery->catchUploadForm( $upload_form, true )) ) {

			$image->getThumbnail(
				$config->getDefaultThbMaxW(),
				$config->getDefaultThbMaxH()
			);

			$this->responseOK();
		} else {
			$this->responseFormErrors( $upload_form->getAllErrors() );
		}


	}

	/**
	 * @param string $image_ID
	 */
	public function put_copy_image_Action( $image_ID ) {
		$image = $this->_getImage( $image_ID );
		$data = $this->getRequestData();
		$gallery = $this->_getGallery( $data['target_gallery_ID'] );

		/**
		 * @var Config $config
		 */
		$config = $this->module_instance->getConfig();

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
			$config->getDefaultThbMaxW(),
			$config->getDefaultThbMaxH()
		);

		$this->responseOK();
	}

	/**
	 * @param string $image_ID
	 */
	public function delete_image_Action( $image_ID ) {
		$image = $this->_getImage($image_ID);

		$image->delete();
		$this->responseOK();
	}

	/**
	 * @param string $image_ID
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 */
	public function get_image_thumbnail_Action( $image_ID, $maximal_size_w, $maximal_size_h ) {
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		if(!$maximal_size_w || !$maximal_size_h) {
			$this->responseUnknownItem($image_ID);
		}

		$image = $this->_getImage($image_ID);

		$URI = $image->getThumbnail($maximal_size_w, $maximal_size_h)->getURI();

		Http_Headers::movedPermanently( $URI );
	}


	/**
	 * @param null|int $ID
	 */
	public function get_gallery_Action( $ID=null ) {
		if($ID) {
			$gallery = $this->_getGallery($ID);
			$this->responseData($gallery);
		} else {
			$this->responseData( Gallery::getTree() );
		}
	}

	/**
	 * @param string $parent_ID (optional)
	 */
	public function get_gallery_tree_lazy_action( $parent_ID="" ) {
		$tree = Gallery::getTree();

		if($parent_ID) {
			$node = $tree->getNode( $parent_ID );
			if(!$node) {
				$this->responseUnknownItem( $parent_ID );
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
			$gallery->validateProperties();
			$gallery->save();
			Mvc::truncateRouterCache();

			$this->responseData($gallery);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	/**
	 * @param $ID
	 */
	public function put_gallery_Action( $ID ) {
		$gallery = $this->_getGallery($ID);

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->validateProperties();
			$gallery->save();

			Mvc::truncateRouterCache();

			$this->responseData($gallery);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	/**
	 * @param string $ID
	 */
	public function delete_gallery_Action( $ID ) {
		$gallery = $this->_getGallery($ID);

		$gallery->delete();
		Mvc::truncateRouterCache();

		$this->responseOK();

	}

	/**
	 * @param $ID
	 * @return Gallery
	 */
	protected  function _getGallery($ID) {
		$gallery = Gallery::get($ID);

		if(!$gallery) {
			$this->responseUnknownItem($ID);
		}

		return $gallery;
	}

	/**
	 * @param $ID
	 * @return Gallery_Image
	 */
	protected  function _getImage($ID) {
		$gallery = Gallery_Image::get($ID);

		if(!$gallery) {
			$this->responseUnknownItem($ID);
		}

		return $gallery;
	}

}