<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc;
use Jet\Data_Paginator;

class Controller_Public_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

    /**
     * @var int
     */
    protected $public_list_items_per_page = 20;

	protected static $ACL_actions_check_map = [
		'default' => false,
		'list' => false,
		'detail' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}

    /**
     * @param Mvc_Page_Content_Interface $page_content
     *
     * @return bool
     */
    public function parseRequestURL_Public( Mvc_Page_Content_Interface $page_content ) {

        $router = Mvc::getCurrentRouter();

        if( count($router->getPathFragments())>1 ) {
            return false;
        }

        $page_no = $router->parsePathFragmentIntValue( 'page:%VAL%' );

        if($page_no>0) {
            $page_content->setControllerAction('list');

            return true;
        } else {
            $article = new Article();
            $current_article = $article->resolveArticleByURL( $router );

            if(!$current_article) {
                return false;
            }

            $page_content->setControllerAction('detail');
            $page_content->setControllerActionParameters( [
                'article' => $current_article
            ] );

            return true;
        }

    }


    /**
     *
     */
    public function default_Action() {
        $this->list_Action();
	}

    /**
     *
     */
    public function list_Action() {
        $article = new Article();
        $router = Mvc::getCurrentRouter();

        $paginator = new Data_Paginator(
            $router->parsePathFragmentIntValue( 'page:%VAL%', 1 ),
            $this->public_list_items_per_page,
            Mvc::getCurrentPage()->getURI().'page:'.Data_Paginator::URL_PAGE_NO_KEY.'/'
        );


        $paginator->setDataSource( $article->getListForCurrentLocale() );

        $articles_list = $paginator->getData();

		$this->view->setVar('articles_list', $articles_list);
		$this->view->setVar('paginator', $paginator);

		$this->render('list');

	}

    /**
     *
     */
    public function detail_Action() {
        /**
         * @var Article $article
         */
        $article = $this->getActionParameterValue('article');

        Mvc::getCurrentPage()->addBreadcrumbNavigationData($article->getTitle());

		$this->view->setVar('article', $article);

		$this->render('detail');
	}
}