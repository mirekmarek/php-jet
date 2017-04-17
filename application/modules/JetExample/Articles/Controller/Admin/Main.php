<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Articles;

use JetExampleApp\Mvc_Controller_AdminStandard;

use JetUI\UI;
use JetUI\dataGrid;
use JetUI\breadcrumbNavigation;
use JetUI\messages;

use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\Http_Request;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

class Controller_Admin_Main extends Mvc_Controller_AdminStandard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => 'get_article',
		'add' => 'add_article',
		'edit' => 'update_article',
		'view' => 'get_article',
		'delete' => 'delete_article',
	];


    /**
     *
     * @return Controller_Admin_Main_Router
     */
    public function getControllerRouter() {
    	return $this->module_instance->getAdminControllerRouter();
    }


	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation($current_label='' ) {
		$menu_item = AdminUI_module::getMenuItems()['content/articles'];

		breadcrumbNavigation::addItem(
			UI::icon($menu_item->getIcon()).'&nbsp;&nbsp;'. $menu_item->getLabel(),
			$menu_item->getUrl()
		);

		if($current_label) {
			breadcrumbNavigation::addItem( $current_label );
		}
	}


	/**
	 *
	 */
	public function default_Action() {
		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm('article');
		$this->view->setVar('search_form', $search_form);


		$grid = new dataGrid();

		$grid->setIsPersistent('admin_classic_articles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('title', Tr::_('Title'));
		$grid->addColumn('date_time', Tr::_('Date and time'));


        $list = Article::getList();

		$grid->setData( $list );

		$this->view->setVar('grid', $grid);

		$this->render('default');
	}

	/**
	 *
	 */
	public function add_Action() {
		$this->_setBreadcrumbNavigation( Tr::_('Create a new Article') );

		$article = new Article();

		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->save();

			$this->logAllowedAction( $article );

			messages::success( Tr::_('Article <b>%TITLE%</b> has been created', ['TITLE'=>$article->getTitle() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $article->getId() ) );
		}

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( Tr::_('New article') );


		$this->view->setVar('btn_label', Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('edit');
	}

	/**
     *
	 */
	public function edit_Action() {

        /**
         * @var Article $article
         */
        $article = $this->getActionParameterValue('article');

		$this->_setBreadcrumbNavigation( Tr::_('Edit article <b>%TITLE%</b>', ['TITLE'=>$article->getTitle() ]) );


		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->save();

			$this->logAllowedAction( $article );

			messages::success( Tr::_('Article <b>%TITLE%</b> has been updated', ['TITLE'=>$article->getTitle() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $article->getId() ) );
		}

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $article->getTitle() );

		$this->view->setVar('btn_label', Tr::_('SAVE') );

		$this->view->setVar('form', $form);

		$this->render('edit');
	}

	/**
     *
	 */
	public function view_Action() {

        /**
         * @var Article $article
         */
        $article = $this->getActionParameterValue('article');

		$this->_setBreadcrumbNavigation( Tr::_('Article detail <b>%TITLE%</b>', ['TITLE'=>$article->getTitle() ]) );

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $article->getTitle() );

		$form = $article->getCommonForm();
		$form->setIsReadonly();

		$this->view->setVar('form', $form);

		$this->render('edit');
	}

	/**
     *
	 */
	public function delete_action() {

        /**
         * @var Article $article
         */
        $article = $this->getActionParameterValue('article');

		$this->_setBreadcrumbNavigation( Tr::_('Delete article <b>%TITLE%</b>', ['TITLE'=>$article->getTitle() ]) );

		if( Http_Request::POST()->getString('delete')=='yes' ) {

			$article->delete();

			$this->logAllowedAction( $article );

			messages::info( Tr::_('Article <b>%TITLE%</b> has been deleted', ['TITLE'=>$article->getTitle() ]) );


			Http_Headers::movedTemporary( Mvc::getCurrentPageURI() );
		}


        Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete article');

		$this->view->setVar( 'article', $article );

		$this->render('delete-confirm');
	}


}