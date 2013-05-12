<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Articles
 */
namespace JetApplicationModule\Jet\Articles;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"default" => false
	);


	public function default_Action() {
		$article = new Article();
		$current_article = $article->resolveArticleByURL( $this->router );

		if($current_article) {
			Jet\Mvc::getCurrentUIManagerModuleInstance()->addBreadcrumbNavigationData($current_article->getTitle());

			$this->view->setVar("article", $current_article);

			$this->render("article-detail");
		} else {

			$paginator = new Jet\Data_Paginator(
				Jet\Http_Request::GET()->getInt("p", 1),
				2,
				"?p=".Jet\Data_Paginator::URL_PAGE_NO_KEY
			);

			/** @noinspection PhpParamsInspection */
			$paginator->setDataSource( $article->getListForCurrentLocale() );

			$this->view->setVar("articles_list", $paginator->getData());
			$this->view->setVar("paginator", $paginator);

			$this->render("articles-list");
		}

	}
}