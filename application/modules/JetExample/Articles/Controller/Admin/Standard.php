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
use JetApplicationModule\JetExample\UIElements;
use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_MicroRouter;
use Jet\Mvc_Router_Abstract;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Page_Content_Abstract;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\Http_Request;

class Controller_Admin_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var Mvc_MicroRouter
	 */
	protected $micro_router;

    /**
     * @var Mvc_MicroRouter
     */
    protected $_standard_admin_micro_router;


	protected static $ACL_actions_check_map = array(
		'default' => 'get_article',
		'add' => 'add_article',
		'edit' => 'update_article',
		'view' => 'get_article',
		'delete' => 'delete_article',
	);

	/**
	 *
	 */
	public function initialize() {
		Mvc::checkCurrentContentIsDynamic();
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -2 );
		$this->micro_router = $this->getStandardAdminMicroRouter();
		$this->view->setVar( 'router', $this->micro_router );
	}


    /**
     * @param Mvc_Router_Abstract $router
     *
     * @return Mvc_MicroRouter
     */
    public function getStandardAdminMicroRouter( Mvc_Router_Abstract $router=null ) {
        if($this->_standard_admin_micro_router) {
            return $this->_standard_admin_micro_router;
        }

        if(!$router) {
            $router = Mvc::getCurrentRouter();
        }

        $router = new Mvc_MicroRouter( $router, $this->module_instance );

        $base_URI = Mvc::getCurrentURI();

        $validator = function( &$parameters ) {
            $article = Article::get( $parameters[0] );
            if(!$article) {
                return false;
            }

            $parameters[0] = $article;
            return true;

        };

        $router->addAction('add', '/^add$/', 'add_article', true)
            ->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_article', true)
            ->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($article->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_article', true)
            ->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'view:'.rawurlencode($article->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_article', true)
            ->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($article->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $this->_standard_admin_micro_router = $router;

        return $router;
    }



    /**
     * @param Mvc_Page_Content_Abstract $page_content
     * @return bool
     */
    public function parseRequestURL_Admin( Mvc_Page_Content_Abstract $page_content=null ) {

        $router = $this->getStandardAdminMicroRouter( Mvc::getCurrentRouter() );

        return $router->resolve( $page_content );
    }


	/**
	 *
	 */
	public function default_Action() {

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Application_Modules::getModuleInstance('JetExample.UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_articles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('title', Tr::_('Title'));
		$grid->addColumn('date_time', Tr::_('Date and time'));

		$grid->setData( Article::getList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');
	}

	/**
	 *
	 */
	public function add_Action() {

		$article = new Article();

		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->validateProperties();
			$article->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $article ) );
		}

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( Tr::_('New article') );


		$this->view->setVar('btn_label', Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param  Article $article
	 */
	public function edit_Action( Article $article ) {

		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->validateProperties();
			$article->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $article ) );
		}

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $article->getTitle() );

		$this->view->setVar('btn_label', Tr::_('SAVE') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param  Article $article
	 */
	public function view_Action(  Article $article  ) {

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $article->getTitle() );

		$form = $article->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param  Article $article
	 */
	public function delete_action(  Article $article  ) {


		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$article->delete();

			Http_Headers::movedTemporary( Mvc::getCurrentURI() );
		}


        Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete article');

		$this->view->setVar( 'article', $article );

		$this->render('classic/delete-confirm');
	}


}