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

	/**
	 * @var Jet\Mvc_MicroRouter
	 */
	protected $micro_router;

	protected static $ACL_actions_check_map = array(
		'default' => false,
		'list' => 'get_article',
		'add' => 'add_article',
		'edit' => 'update_article',
		'view' => 'get_article',
		'delete' => 'delete_article',
	);

	/**
	 *
	 */
	public function initialize() {
		Jet\Mvc::setProvidesDynamicContent();
		$this->getFrontController()->breadcrumbNavigationShift( -2 );
		$this->micro_router = $this->module_instance->getMicroRouter();
	}

	/**
	 *
	 */
	public function default_Action() {
	}

	/**
	 *
	 */
	public function list_Action() {

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample\UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_articles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('title', Jet\Tr::_('Title'));
		$grid->addColumn('date_time', Jet\Tr::_('Date and time'));

		$grid->setData( Article::getList() );

		$this->view->setVar('grid', $grid);
		$this->view->setVar( 'router', $this->micro_router );

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
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $article ) );
		}

		$this->getFrontController()->addBreadcrumbNavigationData( Jet\Tr::_('New article') );


		$this->view->setVar('btn_label', Jet\Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param $ID
	 */
	public function edit_Action( $ID ) {

		$article = Article::get( $ID );
		if(!$article) {
			$this->unknownItem_Action();
			return;
		}

		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->validateProperties();
			$article->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $article ) );
		}

		$this->getFrontController()->addBreadcrumbNavigationData( $article->getTitle() );

		$this->view->setVar('btn_label', Jet\Tr::_('SAVE') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param $ID
	 */
	public function view_Action( $ID ) {

		$article = Article::get( $ID );
		if(!$article) {
			$this->unknownItem_Action();
			return;
		}

		$this->getFrontController()->addBreadcrumbNavigationData( $article->getTitle() );

		$form = $article->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');
	}

	/**
	 * @param $ID
	 */
	public function delete_action( $ID ) {

		$article = Article::get( $ID );
		if(!$article) {
			$this->unknownItem_Action();
			return;
		}

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$article->delete();

			Jet\Http_Headers::movedTemporary( Jet\Mvc::getCurrentURI() );
		}


		$this->getFrontController()->addBreadcrumbNavigationData('Delete article');

		$this->view->setVar( 'article', $article );

		$this->render('classic/delete-confirm');
	}

	/**
	 *
	 */
	public function unknownItem_Action() {
		$this->render('classic/unknown-item');

	}

}