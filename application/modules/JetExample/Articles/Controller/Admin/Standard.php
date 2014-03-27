<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Articles
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;
use JetApplicationModule\JetExample\UIElements;

class Controller_Admin_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_article'
	);


	/**
	 *
	 */
	public function default_Action() {

		Jet\Mvc::setProvidesDynamicContent();

		$GET = Jet\Http_Request::GET();

		if( $delete_ID = $GET->getString('delete')) {
			$article = Article::get( $delete_ID );
			if($article) {
				$this->handleDelete($article);

				return;
			}
		}

		$article = false;

		if($GET->exists('new')) {
			$article = new Article();
		} else if( $GET->exists('ID') ) {
			$article = Article::get( $GET->getString('ID') );
		}

		if($article) {
			$this->handleEdit($article);
		} else {
			$this->handleList();
		}

	}

	/**
	 * @param Article $article
	 */
	public function handleDelete( Article $article ) {
		if( !$this->module_instance->checkAclCanDoAction('delete_article') ) {
			return;
		}

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$article->delete();
			Jet\Http_Headers::movedTemporary('?');
		}


		$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('Delete article');

		$this->view->setVar( 'article', $article );

		$this->render('classic/delete-confirm');
	}

	/**
	 * @param Article $article
	 */
	protected function handleEdit( Article $article ) {
		$has_access = false;

		if($article->getIsNew()) {
			if( !$this->module_instance->checkAclCanDoAction('add_article') ) {
				return;
			}
		} else {
			if( $this->module_instance->checkAclCanDoAction('update_article') ) {
				$has_access = true;
			}
		}


		$form = $article->getCommonForm();

		if($has_access) {
			if( $article->catchForm( $form ) ) {
				$article->validateProperties();
				$article->save();
				Jet\Http_Headers::movedTemporary( '?ID='.$article->getID() );
			}
		}


		if($article->getIsNew()) {
			$this->view->setVar('bnt_label', 'ADD');

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('New article');

		} else {
			$this->view->setVar('bnt_label', 'SAVE' );

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $article->getTitle() );
		}


		$this->view->setVar('has_access', $has_access);
		$this->view->setVar('form', $form);
		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -3 );

		$this->render('classic/edit');

	}

	/**
	 *
	 */
	protected function handleList() {
		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample\UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('title', Jet\Tr::_('Title'));
		$grid->addColumn('date_time', Jet\Tr::_('Date and time'));

		$grid->setData( Article::getList() );

		$this->view->setVar('can_add_article', $this->module_instance->checkAclCanDoAction('add_article'));
		$this->view->setVar('can_delete_article', $this->module_instance->checkAclCanDoAction('delete_article'));
		$this->view->setVar('can_update_article', $this->module_instance->checkAclCanDoAction('update_article'));
		$this->view->setVar('grid', $grid);

		$this->render('classic/default');
	}



}