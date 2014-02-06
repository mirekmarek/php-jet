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
	protected function handleEdit( Article $article ) {
		$has_access = false;

		if($article->getIsNew()) {
			if( $this->module_instance->checkAclCanDoAction('add_article') ) {
				$has_access = true;
			}
		} else {
			if( $this->module_instance->checkAclCanDoAction('update_article') ) {
				$has_access = true;
			}
		}

		if(!$has_access) {
			//TODO:
			return;
		}

		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->validateProperties();
			$article->save();
			Jet\Http_Headers::movedTemporary( '?ID='.$article->getID() );
		}


		if($article->getIsNew()) {
			$this->view->setVar('bnt_label', 'ADD');

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('New article');

		} else {
			$this->view->setVar('bnt_label', 'SAVE' );

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $article->getTitle() );
		}


		$this->view->setVar('form', $form);
		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -3 );

		$this->render('classic/edit');

	}

	/**
	 *
	 */
	protected function handleList() {
		$list = Article::getList();


		$list->getQuery()->setOrderBy( $this->getListSort() );

		$paginator = $this->getPaginator( $list );

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		$this->view->setVar('articles', $paginator->getData());
		$this->view->setVar('paginator', $paginator);

		$this->render('classic/default');
	}

	/**
	 * @return string
	 */
	protected function getListSort() {
		$GET = Jet\Http_Request::GET();

		$session = new Jet\Session( 'Articles_admin_list' );

		if( $sort = $GET->getString('sort') ) {
			if( in_array($sort, array('title', '-title', 'date_time', '-date_time')) ) {
				$session->setValue('sort', $sort);
			}
		}

		return $session->getValue('sort', '+title');
	}

	/**
	 * @param Jet\Data_Paginator_DataSource_Interface $list
	 *
	 * @return Jet\Data_Paginator
	 */
	protected function getPaginator( Jet\Data_Paginator_DataSource_Interface $list ) {
		$p = new Jet\Data_Paginator(
			Jet\Http_Request::GET()->getInt('p', 1),
			10,
			'?p='.Jet\Data_Paginator::URL_PAGE_NO_KEY
		);
		$p->setDataSource( $list );

		return $p;

	}

}