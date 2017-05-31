<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Articles;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc;
use Jet\Data_Paginator;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Site_Main extends Mvc_Controller_Standard
{
	protected static $ACL_actions_check_map = [
		'default' => false,
		'list'    => false,
		'detail'  => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;
	/**
	 * @var int
	 */
	protected $public_list_items_per_page = 20;

	/**
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{

		if( preg_match('/^page:([0-9]{1,})$/', $path, $matches) ) {

			$this->content->setControllerAction( 'list' );
			$this->content->setParameter('page_no', $matches[1]);

			return true;
		} else {
			$current_article = Article::resolveArticleByURL( $path, Mvc::getCurrentLocale() );

			if( $current_article ) {
				$this->content->setControllerAction( 'detail' );
				$this->content->setParameter( 'article', $current_article );

				return true;
			}

		}

		return false;
	}


	/**
	 *
	 */
	public function default_Action()
	{
		$this->list_Action();
	}

	/**
	 *
	 */
	public function list_Action()
	{

		$page_no = (int)$this->getParameter('page_no');

		$paginator = new Data_Paginator(
			$page_no,
			$this->public_list_items_per_page,
			function( $page_no ) {
				return Mvc::getCurrentPage()->getURI(['page:'.$page_no]);
			}
		);

		$paginator->setDataSource( Article::getListForCurrentLocale() );


		if(!$paginator->getCurrentPageNoIsInRange()) {

			Http_Headers::movedTemporary(
							($page_no>1) ?
									$paginator->getLastPageURL()
									:
									$paginator->getFirstPageURL()
							);
		}



		$articles_list = $paginator->getData();

		$this->view->setVar( 'articles_list', $articles_list );
		$this->view->setVar( 'paginator', $paginator );

		$this->render( 'list' );

	}

	/**
	 *
	 */
	public function detail_Action()
	{
		/**
		 * @var Article $article
		 */
		$article = $this->getParameter( 'article' );

		Navigation_Breadcrumb::addURL( $article->getTitle() );

		$this->view->setVar( 'article', $article );

		$this->render( 'detail' );
	}
}