<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

use Jet\Mvc_Controller;
use Jet\Mvc_Controller_Router;

use JetApplication\Mvc_Page;

/**
 *
 */
class Controller_Admin_Main_Router extends Mvc_Controller_Router
{


	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{


		parent::__construct( $controller );

		$page = Mvc_Page::get( Main::ADMIN_MAIN_PAGE );
		$router = $this;

		$URI_creator = function( $action, $action_uri, $id = 0 ) use ( $router, $page ) {
			if( !$router->getActionAllowed( $action ) ) {
				return false;
			}


			if( !$id ) {
				return $page->getURI();
			}

			return $page->getURI([$action_uri.':'.$id ]);
		};



		$validator = function( $parameters ) use ($controller) {
			$gallery = Gallery::get( $parameters[0] );
			if( !$gallery ) {
				return false;
			}

			$controller->getContent()->setParameter('gallery', $gallery);

			return true;

		};



		$this->addAction( 'add', '/^add$|^add:([\S]+)$/', Main::ACTION_ADD_GALLERY )->setCreateURICallback(
			function( $parent_id ) use ( $page, $router ) {
				if( !$router->getActionAllowed( 'add' ) ) {
					return false;
				}


				if( !$parent_id ) {
					return $page->getURI(['add']);
				}

				return $page->getURI(['add:'.$parent_id ]);

			}
		)->setParametersValidatorCallback(
			function( $parameters ) use ( $validator, $controller ) {

				if( !$parameters ) {
					return true;
				}

				$gallery = Gallery::get( $parameters[0] );
				if( !$gallery ) {
					return false;
				}

				$controller->getContent()->setParameter('parent_id', $gallery->getId());

				return true;
			}
		);

		$this->addAction( 'edit', '/^edit:([\S]+)$/', Main::ACTION_UPDATE_GALLERY )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator('edit', 'edit', $id);
			}
		)->setParametersValidatorCallback( $validator );

		$this->addAction( 'view', '/^view:([\S]+)$/', Main::ACTION_GET_GALLERY )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator('view', 'view', $id);
			}
		)->setParametersValidatorCallback( $validator );

		$this->addAction( 'delete', '/^delete:([\S]+)$/', Main::ACTION_DELETE_GALLERY )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator('delete', 'delete', $id);
			}
		)->setParametersValidatorCallback( $validator );


		$this->addAction( 'uploadImage', '/^upload-image:([\S]+)$/', Main::ACTION_ADD_IMAGE )
			->setCreateURICallback(
				function( $id ) use ( $URI_creator ) {
					return $URI_creator('uploadImage', 'upload-image', $id);
				}
			)
			->setParametersValidatorCallback( $validator );


		$this->addAction( 'deleteImages', '/^delete-images:([\S]+)$/', Main::ACTION_DELETE_IMAGE )
			->setCreateURICallback(
				function( $id ) use ( $URI_creator ) {
					return $URI_creator('deleteImages', 'delete-images', $id);
				}
			)
			->setParametersValidatorCallback( $validator );

	}


	/**
	 * @param string $parent_id
	 *
	 * @return bool|string
	 */
	public function getAddURI( $parent_id )
	{
		return $this->getActionURI( 'add', $parent_id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getEditOrViewURI( $id )
	{
		if( !( $uri = $this->getEditURI( $id ) ) ) {
			$uri = $this->getViewURI( $id );
		}

		return $uri;
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getEditURI( $id )
	{
		return $this->getActionURI( 'edit', $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getViewURI( $id )
	{
		return $this->getActionURI( 'view', $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getDeleteURI( $id )
	{
		return $this->getActionURI( 'delete', $id );
	}



	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getUploadImageURI( $id )
	{
		return $this->getActionURI( 'uploadImage', $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getDeleteImagesURI( $id )
	{
		return $this->getActionURI( 'deleteImages', $id );
	}


}