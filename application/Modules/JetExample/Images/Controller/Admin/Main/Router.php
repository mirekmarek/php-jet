<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

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

		$base_URI = Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI();

		$gallery_validator = function( $parameters ) use ($controller) {
			$gallery = Gallery::get( $parameters[0] );
			if( !$gallery ) {
				return false;
			}

			$controller->getContent()->setParameter('gallery', $gallery);

			return true;

		};

		$this->addAction( 'add', '/^add:([\S]+)$/', Main::ACTION_ADD_GALLERY )->setCreateURICallback(
			function( $parent_id ) use ( $base_URI ) {
				return $base_URI.'add:'.rawurlencode( $parent_id ).'/';
			}
		)->setParametersValidatorCallback(
			function( &$parameters ) use ( $gallery_validator ) {

				$parameters['parent_id'] = $parameters[0];

				if( $parameters[0]==Gallery::ROOT_ID ) {
					return true;
				}

				$gallery = Gallery::get( $parameters[0] );
				if( !$gallery ) {
					unset( $parameters['parent_id'] );

					return false;
				}

				return true;
			}
		);

		$this->addAction( 'edit', '/^edit:([\S]+)$/', Main::ACTION_UPDATE_GALLERY )->setCreateURICallback(
			function( $id ) use ( $base_URI ) {
				return $base_URI.'edit:'.rawurlencode( $id ).'/';
			}
		)->setParametersValidatorCallback( $gallery_validator );

		$this->addAction( 'view', '/^view:([\S]+)$/', Main::ACTION_GET_GALLERY )->setCreateURICallback(
			function( $id ) use ( $base_URI ) {
				return $base_URI.'view:'.rawurlencode( $id ).'/';
			}
		)->setParametersValidatorCallback( $gallery_validator );

		$this->addAction( 'delete', '/^delete:([\S]+)$/', Main::ACTION_DELETE_GALLERY )->setCreateURICallback(
			function( $id ) use ( $base_URI ) {
				return $base_URI.'delete:'.rawurlencode( $id ).'/';
			}
		)->setParametersValidatorCallback( $gallery_validator );
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

}