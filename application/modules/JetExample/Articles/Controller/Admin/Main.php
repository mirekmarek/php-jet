<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Mvc_Controller_AdminStandard;

use JetUI\UI;
use JetUI\dataGrid;
use JetUI\breadcrumbNavigation;
use JetUI\messages;

use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

/**
 *
 */
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
		'default' => Main::ACTION_GET_ARTICLE,
		'add' => Main::ACTION_ADD_ARTICLE,
		'edit' => Main::ACTION_UPDATE_ARTICLE,
		'view' => Main::ACTION_GET_ARTICLE,
		'delete' => Main::ACTION_DELETE_ARTICLE,
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
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc_Page::get(Main::ADMIN_MAIN_PAGE);

		breadcrumbNavigation::addItem(
			UI::icon($page->getIcon()).'&nbsp;&nbsp;'. $page->getBreadcrumbTitle(),
			$page->getURL()
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
		$form->getField('locale')->setDefaultValue(Mvc::getCurrentLocale());
		$form->getField('date_time')->setDefaultValue( Data_DateTime::now() );

		if( $article->catchForm( $form ) ) {
			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );

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

			$this->logAllowedAction( 'Article updated', $article->getId(), $article->getTitle(), $article );

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

			$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

			messages::info( Tr::_('Article <b>%TITLE%</b> has been deleted', ['TITLE'=>$article->getTitle() ]) );


			Http_Headers::movedTemporary( Mvc::getCurrentPage()->getURI() );
		}


        Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete article');

		$this->view->setVar( 'article', $article );

		$this->render('delete-confirm');
	}


}