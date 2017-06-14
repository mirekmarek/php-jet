<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Articles;

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
				return $page->getURI([ $action_uri ]);
			}

			return $page->getURI([$action_uri.':'.$id ]);
		};


		$validator = function( $parameters ) use ($controller) {
			$article = Article::get( $parameters[0] );
			if( !$article ) {
				return false;
			}

			$controller->getContent()->setParameter('article', $article);

			return true;

		};

		$this->addAction( 'add', '/^add$/', Main::ACTION_ADD_ARTICLE )->setURICreator(
			function() use ( $URI_creator ) {
				return $URI_creator('add', 'add');
			}
		);

		$this->addAction( 'edit', '/^edit:([\S]+)$/', Main::ACTION_UPDATE_ARTICLE )->setURICreator(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator('edit', 'edit', $id);
			}
		)->setValidator( $validator );

		$this->addAction( 'view', '/^view:([\S]+)$/', Main::ACTION_GET_ARTICLE )->setURICreator(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator('view', 'view', $id);
			}
		)->setValidator( $validator );

		$this->addAction( 'delete', '/^delete$/', Main::ACTION_DELETE_ARTICLE )->setURICreator(
			function() use ( $URI_creator ) {
				return $URI_creator('delete', 'delete' );
			}
		);
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI()
	{
		return $this->getActionURI( 'add' );
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
	 *
	 * @return bool|string
	 */
	public function getDeleteURI()
	{
		return $this->getActionURI( 'delete' );
	}

}