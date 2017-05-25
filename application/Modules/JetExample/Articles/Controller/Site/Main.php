<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

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
		'default' => false, 'list' => false, 'detail' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;
	/**
	 * @var int
	 */
	protected $public_list_items_per_page = 20;

	/**
	 * @param Mvc_Page_Content_Interface $page_content
	 *
	 * @return bool
	 */
	public function parseRequestPath( Mvc_Page_Content_Interface $page_content )
	{
		$path = Mvc::getRouter()->getPath();

		if( preg_match('/^page:([0-9]{1,})$/', $path, $matches) ) {
			$page_content->setControllerAction( 'list' );
			$page_content->setControllerActionParameters(
				[
					'page_no' => $matches[1],
				]
			);

			return true;
		} else {
			$article = new Article();
			$current_article = $article->resolveArticleByURL( $path );

			if( !$current_article ) {
				return false;
			}

			$page_content->setControllerAction( 'detail' );
			$page_content->setControllerActionParameters(
				[
					'article' => $current_article,
				]
			);

			return true;
		}

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
		$article = new Article();

		$paginator = new Data_Paginator(
			$this->public_list_items_per_page,
			$this->getActionParameterValue('page_no'),
			function( $page_no ) {
				return Mvc::getCurrentPage()->getURI(['page:'.$page_no]);
			}
		);


		$paginator->setDataSource( $article->getListForCurrentLocale() );

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
		$article = $this->getActionParameterValue( 'article' );

		Navigation_Breadcrumb::addURL( $article->getTitle() );

		$this->view->setVar( 'article', $article );

		$this->render( 'detail' );
	}
}