<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Controller_REST;
use Jet\Form_Field_FileImage;

/**
 *
 */
class Controller_Admin_REST extends Mvc_Controller_REST
{
	const ERR_CODE_NO_FILE = 'NoFile';
	const ERR_CODE_IMAGE_ALREADY_EXISTS = 'ImageAlreadyExists';
	const ERR_CODE_UNKNOWN_ERROR = 'UnknownError';
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'get_gallery'      => Main::ACTION_GET_GALLERY,
		'get_gallery_tree' => Main::ACTION_GET_GALLERY,
		'put_gallery'      => Main::ACTION_UPDATE_GALLERY,
		'delete_gallery'   => Main::ACTION_DELETE_GALLERY,

		'get_image'           => Main::ACTION_GET_GALLERY,
		'get_image_thumbnail' => Main::ACTION_GET_GALLERY,
		'post_image'          => Main::ACTION_ADD_IMAGE,
		'delete_image'        => Main::ACTION_DELETE_IMAGE,

	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	//TODO: kompletne prepracovat

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		$path_fragments = explode('/', $path);

		$object = array_shift($path_fragments);

		$method = strtolower( Http_Request::getRequestMethod() );

		$controller_action = $method.'_'.$object;

		if(!method_exists($this, $controller_action.'_Action')) {
			return false;
		}


		$this->getContent()->setControllerAction( $controller_action );
		$this->getContent()->setParameters( $path_fragments );

		return true;

	}

	/**
	 * @param null|int $id
	 */
	public function get_image_Action( $id = null )
	{
		if( $id ) {
			$image = $this->_getImage( $id );
			$this->responseData( $image );
		} else {
			$gallery_id = Http_Request::GET()->getString( 'gallery_id' );

			$list = Gallery_Image::getList( $gallery_id );

			$this->responseDataModelsList( $list );

		}
	}

	/**
	 * @param $id
	 *
	 * @return Gallery_Image
	 */
	protected function _getImage( $id )
	{
		$gallery = Gallery_Image::get( $id );

		if( !$gallery ) {
			$this->responseUnknownItem( $id );
		}

		return $gallery;
	}

	/**
	 * @param string $gallery_id
	 */
	public function post_image_Action( $gallery_id )
	{
		$gallery = $this->_getGallery( $gallery_id );

		$upload_form = $gallery->getUploadForm();

		/**
		 * @var Form_Field_FileImage $image_field
		 */
		$image_field = $upload_form->getField( 'file' );

		$image_field->setMaximalSize(
			Config::getDefaultMaxW(), Config::getDefaultMaxH()
		);

		if( ( $image = $gallery->catchUploadForm( $upload_form, true ) ) ) {
			$this->logAllowedAction(
				'Image created',
				$image->getIdObject()->toString(), $image->getFileName(), $image
			);


			$this->responseOK();
		} else {
			$this->responseValidationError( $upload_form->getAllErrors() );
		}


	}

	/**
	 * @param $id
	 *
	 * @return Gallery
	 */
	protected function _getGallery( $id )
	{
		$gallery = Gallery::get( $id );

		if( !$gallery ) {
			$this->responseUnknownItem( $id );
		}

		return $gallery;
	}


	/**
	 * @param string $image_id
	 */
	public function delete_image_Action( $image_id )
	{
		$image = $this->_getImage( $image_id );

		$this->logAllowedAction( 'Image deleted', $image->getIdObject()->toString(), $image->getFileName(), $image );

		$image->delete();
		$this->responseOK();
	}

	/**
	 * @param string $image_id
	 * @param int    $maximal_size_w
	 * @param int    $maximal_size_h
	 */
	public function get_image_thumbnail_Action( $image_id, $maximal_size_w, $maximal_size_h )
	{
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		if( !$maximal_size_w || !$maximal_size_h ) {
			$this->responseUnknownItem( $image_id );
		}

		$image = $this->_getImage( $image_id );

		$URI = $image->getThumbnail( $maximal_size_w, $maximal_size_h );

		Http_Headers::movedPermanently( $URI );
	}

	/**
	 * @param null|int $id
	 */
	public function get_gallery_Action( $id = null )
	{
		if( $id ) {
			$gallery = $this->_getGallery( $id );
			$this->responseData( $gallery );
		} else {
			$this->responseData( Gallery::getTree() );
		}
	}


	/**
	 *
	 */
	public function get_gallery_tree_action()
	{
		$tree = Gallery::getTree();

		$this->responseData( $tree );

	}

	/**
	 *
	 */
	public function post_gallery_Action()
	{
		$gallery = new Gallery();

		$form = $gallery->getAddForm();

		if( $gallery->catchAddForm() ) {
			$this->logAllowedAction( 'Gallery created', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}

	}

	/**
	 * @param $id
	 */
	public function put_gallery_Action( $id )
	{
		$gallery = $this->_getGallery( $id );

		$form = $gallery->getEditForm();

		if( $gallery->catchEditForm() ) {
			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			$gallery->save();

			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}
	}

	/**
	 * @param string $id
	 */
	public function delete_gallery_Action( $id )
	{
		$gallery = $this->_getGallery( $id );

		$this->logAllowedAction( 'Gallery deleted', $gallery->getId(), $gallery->getTitle(), $gallery );

		$gallery->delete();

		$this->responseOK();

	}

}