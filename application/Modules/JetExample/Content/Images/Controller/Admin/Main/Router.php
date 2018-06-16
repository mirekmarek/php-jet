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
use Jet\Mvc_Controller_Router_Action;

use Jet\Mvc_Page;

/**
 *
 */
class Controller_Admin_Main_Router extends Mvc_Controller_Router
{

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $add;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $edit;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $view;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $delete;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $upload_image;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $delete_images;


	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{


		parent::__construct( $controller );

		$validator = function( $parameters ) use ($controller) {
			$gallery = Gallery::get( $parameters[0] );
			if( !$gallery ) {
				return false;
			}

			$controller->getContent()->setParameter('gallery', $gallery);

			return true;

		};



		$this->add = $this->addAction( 'add', '/^add$|^add:([\S]+)$/' );
		$this->add->setURICreator(
			function( $parent_id ) {
				if( !$parent_id ) {
					return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['add']);
				}

				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['add:'.$parent_id]);

			}
		);
		$this->add->setValidator(
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

		$this->edit = $this->addAction( 'edit', '/^edit:([\S]+)$/' );
		$this->edit->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['edit:'.$id]);
			}
		);
		$this->edit->setValidator( $validator );



		$this->view = $this->addAction( 'view', '/^view:([\S]+)$/' );
		$this->view->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['view:'.$id]);
			}
		);
		$this->view->setValidator( $validator );



		$this->delete = $this->addAction( 'delete', '/^delete:([\S]+)$/' );
		$this->delete->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['delete:'.$id]);
			}
		);
		$this->delete->setValidator( $validator );


		$this->upload_image = $this->addAction( 'uploadImage', '/^upload-image:([\S]+)$/' );
		$this->upload_image->setURICreator(
				function( $id ) {
					return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['upload-image:'.$id]);
				}
			);
		$this->upload_image->setValidator( $validator );


		$this->delete_images = $this->addAction( 'deleteImages', '/^delete-images:([\S]+)$/' );
		$this->delete_images->setURICreator(
				function( $id ) {
					return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['delete-images:'.$id]);
				}
			);
		$this->delete_images->setValidator( $validator );

	}


	/**
	 * @param string $parent_id
	 *
	 * @return bool|string
	 */
	public function getAddURI( $parent_id='' )
	{
		return $this->add->URI( $parent_id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getEditURI( $id )
	{
		return $this->edit->URI( $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getViewURI( $id )
	{
		return $this->view->URI( $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getDeleteURI( $id )
	{
		return $this->delete->URI( $id );
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
	public function getUploadImageURI( $id )
	{
		return $this->upload_image->URI( $id );
	}

	/**
	 * @param string $id
	 *
	 * @return bool|string
	 */
	public function getDeleteImagesURI( $id )
	{
		return $this->delete_images->URI( $id );
	}


}