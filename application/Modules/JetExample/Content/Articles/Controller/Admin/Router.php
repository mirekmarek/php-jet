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
use Jet\Mvc_Controller_Router_Action;

use Jet\Mvc_Page;

/**
 *
 */
class Controller_Admin_Router extends Mvc_Controller_Router
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
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{

		parent::__construct( $controller );


		$validator = function( $parameters ) use ($controller) {
			$article = Article::get( $parameters[0] );
			if( !$article ) {
				return false;
			}

			$controller->getContent()->setParameter('article', $article);

			return true;

		};

		$this->add = $this->addAction( 'add', '/^add$/' );
		$this->add->setURICreator(
			function() {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['add']);
			}
		);

		$this->edit = $this->addAction( 'edit', '/^edit:([\S]+)$/' );
		$this->edit->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['edit:'.$id]);
			}
		);
		$this->edit->setValidator( $validator );


		$this->view = $this->addAction( 'view', '/^view:([\S]+)$/' )->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['view:'.$id]);
			}
		);
		$this->view->setValidator( $validator );



		$this->delete = $this->addAction( 'delete', '/^delete$/' );
		$this->delete->setURICreator(
			function() {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['delete']);
			}
		);
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI()
	{
		return $this->add->URI();
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
	 *
	 * @return bool|string
	 */
	public function getDeleteURI()
	{
		return $this->delete->URI();
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

}