<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;

class Controller_Public_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

    /**
     * @var int
     */
    protected $public_list_items_per_page = 2;

	protected static $ACL_actions_check_map = array(
		'default' => false,
		'list' => false,
		'detail' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}

    /**
     * @param Jet\Mvc_Page_Content_Abstract $page_content
     *
     * @return bool
     */
    public function parseRequestURL_Public( Jet\Mvc_Page_Content_Abstract $page_content ) {

        $router = Jet\Mvc::getCurrentRouter();

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
            $page_content->setControllerActionParameters( [$current_article] );

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
        $router = Jet\Mvc::getCurrentRouter();

        $paginator = new Jet\Data_Paginator(
            $router->parsePathFragmentIntValue( 'page:%VAL%', 1 ),
            $this->public_list_items_per_page,
            Jet\Mvc::getCurrentPage()->getURI().'page:'.Jet\Data_Paginator::URL_PAGE_NO_KEY.'/'
        );


        $paginator->setDataSource( $article->getListForCurrentLocale() );

        $articles_list = $paginator->getData();

		$this->view->setVar('articles_list', $articles_list);
		$this->view->setVar('paginator', $paginator);

		$this->render('list');

	}

    /**
     * @param Article $article
     */
    public function detail_Action( Article $article ) {
        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData($article->getTitle());

		$this->view->setVar('article', $article);

		$this->render('detail');
	}
}