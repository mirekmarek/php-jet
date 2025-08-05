<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Web;

use Jet\Debug;
use JetApplicationModule\Content\Articles\Entity\Article;

use Jet\Http_Headers;
use Jet\MVC_Controller_Default;
use Jet\MVC;
use Jet\Data_Paginator;
use Jet\MVC_Controller_Router;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	/**
	 * @var ?MVC_Controller_Router
	 */
	protected ?MVC_Controller_Router $router = null;

	/**
	 * @var int
	 */
	protected int $public_list_items_per_page = 20;

	/**
	 * @var int
	 */
	protected int $page_no = 1;

	/**
	 * @var ?Article
	 */
	protected ?Article $article = null;

	/**
	 * @return MVC_Controller_Router
	 */
	public function getControllerRouter(): MVC_Controller_Router
	{
		if( !$this->router ) {
			$this->router = new MVC_Controller_Router( $this );


			$this->router->addAction( 'list' )
				->setResolver( function() {
					$path = MVC::getRouter()->getUrlPath();
				
					if( $path == '' ) {
						return true;
					}
					if( preg_match( '/^page:([0-9]+)$/', $path, $matches ) ) {
						$this->page_no = (int)$matches[1];
						MVC::getRouter()->setUsedUrlPath( $path );
						return true;
					}

					return false;
				} );
			$this->router->addAction( 'detail' )
				->setResolver( function() {
					$path = MVC::getRouter()->getUrlPath();
					
					if( $path == '' ) {
						return false;
					}

					$current_article = Article::resolveArticleByURL( $path, MVC::getLocale() );

					if( !$current_article ) {
						return false;
					}
					$this->article = $current_article;
					MVC::getRouter()->setUsedUrlPath( $path );

					MVC::getPage()->setCacheContext($current_article->getId());

					return true;

				} );
		}

		return $this->router;
	}


	/**
	 *
	 */
	public function list_Action(): void
	{
		$page_no = $this->page_no;

		$paginator = new Data_Paginator(
			$page_no,
			$this->public_list_items_per_page,
			function( $page_no ) {
				return MVC::getPage()->getURLPath( ['page:' . $page_no] );
			}
		);
		
		Debug::varDump( $paginator, 'Paginator 1' );
		Debug::varDump( $page_no, 'Page NO' );

		$paginator->setDataSource( Article::getListForCurrentLocale() );


		if( !$paginator->getCurrentPageNoIsInRange() ) {

			Http_Headers::movedTemporary(
				($page_no > 1)
					?
					$paginator->getLastPageURL()
					:
					$paginator->getFirstPageURL()
			);
		}


		$articles_list = $paginator->getData();

		$this->view->setVar( 'articles_list', $articles_list );
		$this->view->setVar( 'paginator', $paginator );

		$this->output( 'list' );
		
		/*
		Debug::varDump( $articles_list );
		Debug::varDump([
			'a' => 'lorem',
			'b' => 'ipsum',
			1 => null
		] );
		*/
	}

	/**
	 *
	 */
	public function detail_Action(): void
	{
		$article = $this->article;

		Navigation_Breadcrumb::addURL( $article->getTitle() );

		$this->view->setVar( 'article', $article );

		$this->output( 'detail' );
	}
}