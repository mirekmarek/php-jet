<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

use Jet\Http_Request;
use Jet\Mvc_Controller_REST;

/**
 *
 */
class Controller_REST_Main extends Mvc_Controller_REST
{

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'get_galleries'    => Main::ACTION_GET_GALLERY,
		'get_gallery'      => Main::ACTION_GET_GALLERY,
		'add_gallery'      => Main::ACTION_ADD_GALLERY,
		'update_gallery'   => Main::ACTION_UPDATE_GALLERY,
		'delete_gallery'   => Main::ACTION_DELETE_GALLERY,

		'get_images'       => Main::ACTION_GET_GALLERY,
		'get_image'        => Main::ACTION_GET_GALLERY,
		'add_image'        => Main::ACTION_ADD_IMAGE,
		'delete_image'     => Main::ACTION_DELETE_IMAGE,

	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		$path_fragments = explode('/', $path);

		$object = array_shift($path_fragments);

		if($object!='gallery') {
			return false;
		}

		$method = $this->getRequestMethod();


		$gallery = null;
		$image = null;
		$sub_object = null;

		if($path_fragments) {
			$gallery_id = array_shift($path_fragments);
			$gallery = Gallery::get($gallery_id);

			if(!$gallery) {
				$this->responseUnknownItem($gallery_id);
				return false;
			}

			$this->getContent()->setParameter('gallery', $gallery);

			if($path_fragments) {
				$sub_object = array_shift($path_fragments);
				if($sub_object!='image') {
					return false;
				}

				if($path_fragments) {
					$image_id = array_shift($path_fragments);
					if($path_fragments) {
						return false;
					}

					$image = Gallery_Image::get($image_id);
					if(!$image) {
						$this->responseUnknownItem($image_id);

						return false;
					}

					if($image->getGalleryId()!=$gallery->getId()) {
						$this->responseUnknownItem($image_id);

						return false;
					}

				}
			}
		}



		switch( $method ) {
			case self::REQUEST_METHOD_GET:

				$controller_action = 'get_galleries';
				if($gallery) {
					$controller_action = 'get_gallery';
					$this->getContent()->setParameter('gallery', $gallery);
				}

				if($sub_object) {
					$controller_action = 'get_images';
					if($image) {
						$controller_action = 'get_image';
						$this->getContent()->setParameter('image', $image);
					}
				}
			break;
			case self::REQUEST_METHOD_POST:
				$controller_action = 'add_gallery';

				if($gallery) {
					if(!$sub_object) {
						return false;
					}

					if($image) {
						return false;
					}

					$this->getContent()->setParameter('gallery', $gallery);

					$controller_action = 'add_image';
				}
			break;
			case self::REQUEST_METHOD_PUT:
				if(!$gallery) {
					return false;
				}

				if($sub_object) {
					return false;
				}
				$this->getContent()->setParameter('gallery', $gallery);

				$controller_action = 'update_gallery';

			break;
			case self::REQUEST_METHOD_DELETE:
				if(!$gallery) {
					return false;
				}
				$controller_action = 'delete_gallery';
				$this->getContent()->setParameter('gallery', $gallery);


				if($sub_object) {
					if(!$image) {
						return false;
					}

					$controller_action = 'delete_image';
					$this->getContent()->setParameter('image', $image);
				}


				break;
			default:
				return false;
		}

		$this->getContent()->setControllerAction( $controller_action );

		return true;
	}








	/**
	 *
	 */
	public function get_galleries_Action( )
	{
		if(Http_Request::GET()->exists('tree')) {
			$this->responseData( Gallery::getTree() );
		} else {
			$this->responseData(
				$this->handleDataPagination(
					$this->handleOrderBy(
						Gallery::getList(),
						[
							'title' => 'gallery_localized.title'
						]
					)
				)
			);

		}
	}

	/**
	 *
	 */
	public function get_gallery_Action( )
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter('gallery');
		$this->responseData( $gallery );
	}

	/**
	 *
	 */
	public function add_gallery_Action()
	{
		$gallery = new Gallery();

		$form = $gallery->getAddForm();
		$form->catchInput( $this->getRequestData(), true );

		if( $form->validate() ) {
			$form->catchData();

			$gallery->save();

			$this->logAllowedAction( 'Gallery created', $gallery->getId(), $gallery->getTitle(), $gallery );


			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}

	}

	/**
	 *
	 */
	public function update_gallery_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter('gallery');

		$form = $gallery->getEditForm();
		$form->catchInput( $this->getRequestData(), true );

		if( $form->validate() ) {
			$form->catchData();

			$gallery->save();

			$this->logAllowedAction( 'Gallery updated', $gallery->getId(), $gallery->getTitle(), $gallery );

			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}
	}

	/**
	 *
	 */
	public function delete_gallery_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter('gallery');

		$this->logAllowedAction( 'Gallery deleted', $gallery->getId(), $gallery->getTitle(), $gallery );

		$gallery->delete();

		$this->responseOK();

	}




	/**
	 *
	 */
	public function get_images_Action()
	{
		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter('gallery');

		$list = Gallery_Image::getList( $gallery->getId() );

		$this->responseData(
			$this->handleDataPagination(
				$this->handleOrderBy(
					$list,
					[
						'file_name' => 'image.file_name',
					    'file_size' => 'image.file_size'
					]
				)

			)
		);

	}

	/**
	 *
	 */
	public function get_image_Action()
	{
		/**
		 * @var Gallery_Image $image
		 */
		$image = $this->getParameter('image');

		if(
			($thb=Http_Request::GET()->getString('thumbnail')) &&
			preg_match('/^([0-9]{1,})x([0-9]{1,})$/', $thb)
		) {
			list( $max_w, $max_h ) = explode('x', $thb);

			$thb = $image->getThumbnail($max_w, $max_h);

			$this->responseData( $thb );
		}

		$this->responseData( $image );
	}


	/**
	 *
	 */
	public function add_image_Action()
	{

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter('gallery');

		$upload_form = $gallery->getImageUploadForm();


		if( ( $image = $gallery->catchImageUploadForm( true ) ) ) {
			$this->logAllowedAction(
				'Image created',
				$image->getId(),
				$image->getFileName(),
				$image
			);

			$this->responseData( $image );
		} else {
			$this->responseValidationError( $upload_form->getAllErrors() );
		}
	}

	/**
	 *
	 */
	public function delete_image_Action()
	{
		/**
		 * @var Gallery_Image $image
		 */
		$image = $this->getParameter('image');

		$image->delete();

		$this->logAllowedAction( 'Image deleted', $image->getIdObject()->toString(), $image->getFileName(), $image );

		$this->responseOK();
	}


}