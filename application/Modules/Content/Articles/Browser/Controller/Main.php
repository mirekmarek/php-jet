<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Browser;

use JetApplication\Content_Article;

use Jet\Http_Headers;
use Jet\Mvc_Controller_Default;
use Jet\Mvc;
use Jet\Data_Paginator;
use Jet\Mvc_Controller_Router;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 * @var ?Mvc_Controller_Router
	 */
	protected ?Mvc_Controller_Router $router = null;

	/**
	 * @var int
	 */
	protected int $public_list_items_per_page = 20;

	/**
	 * @var int
	 */
	protected int $page_no = 1;

	/**
	 * @var ?Content_Article
	 */
	protected ?Content_Article $article = null;

	/**
	 * @return Mvc_Controller_Router
	 */
	public function getControllerRouter(): Mvc_Controller_Router
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router( $this );

			$path = Mvc::getRouter()->getUrlPath();

			$this->router->addAction( 'list' )
				->setResolver( function() use ( $path ) {
					if( $path == '' ) {
						return true;
					}
					if( preg_match( '/^page:([0-9]+)$/', $path, $matches ) ) {
						$this->page_no = $matches[1];
						Mvc::getRouter()->setUsedUrlPath( $path );
						return true;
					}

					return false;
				} );
			$this->router->addAction( 'detail' )
				->setResolver( function() use ( $path ) {

					$current_article = Content_Article::resolveArticleByURL( $path, Mvc::getLocale() );

					if( !$current_article ) {
						return false;
					}
					$this->article = $current_article;
					Mvc::getRouter()->setUsedUrlPath( $path );

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
				return Mvc::getPage()->getURLPath( ['page:' . $page_no] );
			}
		);

		$paginator->setDataSource( Content_Article::getListForCurrentLocale() );


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