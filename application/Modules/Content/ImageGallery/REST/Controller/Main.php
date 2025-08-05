<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\ImageGallery\REST;

use Jet\Http_Request;
use Jet\Logger;
use Jet\MVC_Controller_REST;
use Jet\MVC_Controller_REST_Router;
use JetApplicationModule\Content\ImageGallery\Entity\Gallery;
use JetApplicationModule\Content\ImageGallery\Entity\Gallery_Image;

/**
 *
 */
class Controller_Main extends MVC_Controller_REST
{

	/**
	 * @var ?Gallery
	 */
	protected ?Gallery $gallery = null;

	/**
	 * @var string
	 */
	protected string $sub_object = '';

	/**
	 * @var ?Gallery_Image
	 */
	protected ?Gallery_Image $image = null;

	/**
	 * @return MVC_Controller_REST_Router
	 */
	public function getControllerRouter(): MVC_Controller_REST_Router
	{
		$router = new MVC_Controller_REST_Router(
			$this,
			[
				'get_galleries'  => Main::ACTION_GET_GALLERY,
				'get_gallery'    => Main::ACTION_GET_GALLERY,
				'add_gallery'    => Main::ACTION_ADD_GALLERY,
				'update_gallery' => Main::ACTION_UPDATE_GALLERY,
				'delete_gallery' => Main::ACTION_DELETE_GALLERY,

				'get_images'   => Main::ACTION_GET_GALLERY,
				'get_image'    => Main::ACTION_GET_GALLERY,
				'add_image'    => Main::ACTION_ADD_IMAGE,
				'delete_image' => Main::ACTION_DELETE_IMAGE,
			]
		);

		$router
			->setPreparer( function( $path ) {
				if( !$path ) {
					return true;
				}

				$path_fragments = explode( '/', $path );

				$gallery_id = array_shift( $path_fragments );
				$this->gallery = Gallery::get( $gallery_id );

				if( !$this->gallery ) {
					$this->responseUnknownItem( $gallery_id );
					return false;
				}

				if( !$path_fragments ) {
					return true;
				}

				$this->sub_object = array_shift( $path_fragments );
				if( $this->sub_object != 'image' ) {
					return false;
				}

				if( !$path_fragments ) {
					return true;
				}

				$image_id = array_shift( $path_fragments );
				if( $path_fragments ) {
					return false;
				}

				$this->image = Gallery_Image::get( $image_id );
				if( !$this->image ) {
					$this->responseUnknownItem( $image_id );

					return false;
				}

				if( $this->image->getGalleryId() != $this->gallery->getId() ) {
					$this->responseUnknownItem( $image_id );

					return false;
				}


				return true;
			} )
			->setResolverGet( function() {
				$controller_action = 'get_galleries';
				if( $this->gallery ) {
					$controller_action = 'get_gallery';
				}

				if( $this->sub_object ) {
					$controller_action = 'get_images';
					if( $this->image ) {
						$controller_action = 'get_image';
					}
				}

				return $controller_action;
			} )
			->setResolverPost( function() {
				$controller_action = 'add_gallery';

				if( $this->gallery ) {
					if( !$this->sub_object ) {
						return false;
					}

					if( $this->image ) {
						return false;
					}

					$controller_action = 'add_image';
				}

				return $controller_action;
			} )
			->setResolverPut( function() {
				if( !$this->gallery ) {
					return false;
				}

				if( $this->sub_object ) {
					return false;
				}

				return 'update_gallery';
			} )
			->setResolverDelete( function() {
				if( !$this->gallery ) {
					return false;
				}
				$controller_action = 'delete_gallery';


				if( $this->sub_object ) {
					if( !$this->image ) {
						return false;
					}

					$controller_action = 'delete_image';
				}
				return $controller_action;
			} );

		return $router;
	}


	/**
	 *
	 */
	public function get_galleries_Action(): void
	{
		if( Http_Request::GET()->exists( 'tree' ) ) {
			$this->responseData( Gallery::getTree() );
		} else {
			/** @noinspection PhpParamsInspection */
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
	public function get_gallery_Action(): void
	{
		$this->responseData( $this->gallery );
	}

	/**
	 *
	 */
	public function add_gallery_Action(): void
	{
		$gallery = new Gallery();

		$form = $gallery->getAddForm();
		$form->catchInput( $this->getRequestData(), true );

		if( $form->validate() ) {
			$form->catchFieldValues();

			$gallery->save();

			Logger::success(
				event: 'gallery_created',
				event_message: 'Gallery created',
				context_object_id: $gallery->getId(),
				context_object_name: $gallery->getTitle(),
				context_object_data: $gallery
			);


			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getValidationErrors() );
		}

	}

	/**
	 *
	 */
	public function update_gallery_Action(): void
	{
		$gallery = $this->gallery;

		$form = $gallery->getEditForm();
		$form->catchInput( $this->getRequestData(), true );

		if( $form->validate() ) {
			$form->catchFieldValues();

			$gallery->save();

			Logger::success(
				event: 'gallery_updated',
				event_message: 'Gallery updated',
				context_object_id: $gallery->getId(),
				context_object_name: $gallery->getTitle(),
				context_object_data: $gallery
			);

			$this->responseData( $gallery );
		} else {
			$this->responseValidationError( $form->getValidationErrors() );
		}
	}

	/**
	 *
	 */
	public function delete_gallery_Action(): void
	{
		$gallery = $this->gallery;

		Logger::success(
			event: 'gallery_deleted',
			event_message: 'Gallery deleted',
			context_object_id: $gallery->getId(),
			context_object_name: $gallery->getTitle(),
			context_object_data: $gallery
		);

		$gallery->delete();

		$this->responseOK();

	}


	/**
	 *
	 */
	public function get_images_Action(): void
	{
		$gallery = $this->gallery;

		$list = Gallery_Image::getList( $gallery->getId() );

		/** @noinspection PhpParamsInspection */
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
	public function get_image_Action(): void
	{
		$image = $this->image;

		if(
			($thb = Http_Request::GET()->getString( 'thumbnail' )) &&
			preg_match( '/^([0-9]+)x([0-9]+)$/', $thb )
		) {
			[
				$max_w,
				$max_h
			] = explode( 'x', $thb );

			$thb = $image->getThumbnail( (int)$max_w, (int)$max_h );

			$this->responseData( $thb );
		}

		$this->responseData( $image );
	}


	/**
	 *
	 */
	public function add_image_Action(): void
	{
		$gallery = $this->gallery;

		$upload_form = $gallery->getImageUploadForm();


		if( ($images = $gallery->catchImageUploadForm( true )) ) {
			$ids = [];
			$names = [];
			foreach( $images as $i ) {
				$ids[] = $i->getId();
				$names[] = $i->getFileName();
			}


			Logger::success(
				event: 'image_uploaded',
				event_message: 'image uploaded',
				context_object_id: implode( ', ', $ids ),
				context_object_name: implode( ', ', $names )
			);

			$this->responseData( $images[0] );
		} else {
			$this->responseValidationError( $upload_form->getValidationErrors() );
		}
	}

	/**
	 *
	 */
	public function delete_image_Action(): void
	{
		$image = $this->image;

		$image->delete();

		Logger::success(
			event: 'image_deleted',
			event_message: 'Image deleted',
			context_object_id: $image->getId(),
			context_object_name: $image->getFileName(),
			context_object_data: $image
		);

		$this->responseOK();
	}


}