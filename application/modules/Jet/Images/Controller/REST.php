<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Jet\Images
 */
namespace JetApplicationModule\Jet\Images;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	const ERR_CODE_NO_FILE = "NoFile";
	const ERR_CODE_IMAGE_ALLREADY_EXISTS = "ImageAllreadyExists";
	const ERR_CODE_UNKNOWN_ERROR = "UnknownError";

	protected static $ACL_actions_check_map = array(
		"get_image" => "get_image",
		"get_image_thumbnail" => false,
		"post_image" => "add_image",
		"delete_image" => "delete_image",
		"put_copy_image" => "add_image",

		"get_gallery" => "get_gallery",
		"post_gallery" => "add_gallery",
		"put_gallery" => "update_gallery",
		"delete_gallery" => "delete_gallery"
	);

	protected static $errors = array(
		self::ERR_CODE_AUTHORIZATION_REQUIRED => array(Jet\Http_Headers::CODE_401_UNAUTHORIZED, "Access denied! Authorization required! "),
		self::ERR_CODE_ACCESS_DENIED => array(Jet\Http_Headers::CODE_401_UNAUTHORIZED, "Access denied! Insufficient permissions! "),
		self::ERR_CODE_UNSUPPORTED_DATA_CONTENT_TYPE => array(Jet\Http_Headers::CODE_400_BAD_REQUEST, "Unsupported data Content-Type"),
		self::ERR_CODE_FORM_ERRORS => array(Jet\Http_Headers::CODE_400_BAD_REQUEST, "There are errors in form"),
		self::ERR_CODE_UNKNOWN_ITEM => array(Jet\Http_Headers::CODE_404_NOT_FOUND, "Unknown item"),

		self::ERR_CODE_NO_FILE => array( Jet\Http_Headers::CODE_406_NOT_ACCEPTABLE, "No file sent" ),
		self::ERR_CODE_IMAGE_ALLREADY_EXISTS => array( Jet\Http_Headers::CODE_409_CONFLICT, "Image allready uploaded" ),
		self::ERR_CODE_UNKNOWN_ERROR => array(Jet\Http_Headers::CODE_400_BAD_REQUEST, "Unknown error"),
	);

	/**
	 * @param null|int $ID
	 */
	public function get_image_Action( $ID=null ) {
		if($ID) {
			$image = $this->_getImage($ID);
			$this->responseData($image);
		} else {
			$gallery_ID = Jet\Http_Request::GET()->getString("gallery_ID");

			$thumbnail_max_size_w = Jet\Http_Request::GET()->getInt("thumbnail_max_size_w");
			$thumbnail_max_size_h = Jet\Http_Request::GET()->getInt("thumbnail_max_size_h");

			$list = Gallery_Image::getListAsData( $gallery_ID );
			if($thumbnail_max_size_w>0 && $thumbnail_max_size_h>0) {
				$list->setArrayWalkCallback(
					function( &$image_data )
					use ($thumbnail_max_size_w, $thumbnail_max_size_h)
					{
						$image = Gallery_Image::get($image_data["ID"]);

						$image_data["thumbnail_URI"] = $image->getThumbnail($thumbnail_max_size_w, $thumbnail_max_size_h)->getURI();
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

		if(!isset($_FILES["file"])) {
			$this->responseError(
				self::ERR_CODE_NO_FILE
			);
		}

		try {
			$gallery->addImage( $_FILES["file"]["tmp_name"],  $_FILES["file"]["name"], ( isset($_POST["overwrite_if_exists"]) &&  $_POST["overwrite_if_exists"] ) );
		} catch( Exception $e ) {
			if($e->getCode()==Exception::CODE_IMAGE_ALLREADY_EXIST) {
				$this->responseError( self::ERR_CODE_IMAGE_ALLREADY_EXISTS, array("file_name"=>$_FILES["file"]["name"]) );
			} else {
				$this->responseError( self::ERR_CODE_UNKNOWN_ERROR, array("message"=>$e->getMessage()) );
			}
		}

		$this->responseOK();
	}

	/**
	 * @param string $image_ID
	 */
	public function put_copy_image_Action( $image_ID ) {
		$image = $this->_getImage( $image_ID );
		$data = $this->getRequestData();
		$gallery = $this->_getGallery( $data["target_gallery_ID"] );

		//TODO: overwrite ..
		//TODO: check errors ...
		$gallery->addImage( $image->getFilePath(), $image->getFileName() );

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

		Jet\Http_Headers::movedPermanently( $URI );
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
	 *
	 */
	public function post_gallery_Action() {
		$gallery = Gallery::getNew();
		$gallery->initNewObject();

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->validateProperties();
			$gallery->save();

			$this->responseData($gallery);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	public function put_gallery_Action( $ID ) {
		$gallery = $this->_getGallery($ID);

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->validateProperties();
			$gallery->save();
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
		Jet\Mvc::truncateRouterCache();

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