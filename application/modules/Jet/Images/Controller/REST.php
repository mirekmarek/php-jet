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
		"no_file" => array( Jet\Http_Headers::CODE_406_NOT_ACCEPTABLE, "No file sent" )
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

			$data = Gallery_Image::getListAsData( $gallery_ID );

			$response_headers = $this->handleDataPagination( $data );
			$this->handleOrderBy( $data );

			if($thumbnail_max_size_w && $thumbnail_max_size_h) {
				foreach( $data as $i=>$d ) {
					$image = Gallery_Image::get($d["ID"]);

					$d["thumbnail_URI"] = $image->getThumbnail($thumbnail_max_size_w, $thumbnail_max_size_h)->getURI();

					$data[$i] = $d;

				}
			}


			if($this->responseFormatDetection()==static::RESPONSE_FORMAT_XML) {
				$this->_response( $data->toXML(), $response_headers );
			} else {
				$this->_response( $data->toJSON(), $response_headers );
			}
		}
	}

	/**
	 * @param string $gallery_ID
	 */
	public function post_image_Action( $gallery_ID ) {
		$gallery = $this->_getGallery($gallery_ID);

		if(!isset($_FILES["file"])) {
			$this->responseError("no_file");
		}

		//TODO: overwrite ..
		//TODO: check errors ...
		$gallery->addImage( $_FILES["file"]["tmp_name"],  $_FILES["file"]["name"]);

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

	public function post_gallery_Action() {
		$gallery = Gallery::getNew();
		$gallery->initNewObject();

		$form = $gallery->getCommonForm();

		if($gallery->catchForm( $form, $this->getRequestData(), true )) {
			$gallery->validateProperties();
			$gallery->save();

			/*
			//- TMP -
			$files = Jet\IO_Dir::getList(JET_DATA_PATH."SamplePictures");

			foreach( $files as $path=>$file ) {
				$image = Gallery_Image::getNewImage($gallery, $path, true);
				$image->getThumbnail( 50, 50, true);
				$image->getThumbnail( 100, 100, true);
				$image->validateProperties();
				$image->save();
			}
			//- TMP -
			*/

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