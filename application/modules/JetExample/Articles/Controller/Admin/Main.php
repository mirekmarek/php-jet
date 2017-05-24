<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use JetApplication\Mvc_Page;
use JetApplication\Mvc_Controller_AdminStandard;

use Jet\UI;
use Jet\UI_dataGrid;
use Jet\UI_messages;

use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Navigation_Breadcrumb;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

/**
 *
 */
class Controller_Admin_Main extends Mvc_Controller_AdminStandard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => Main::ACTION_GET_ARTICLE, 'add' => Main::ACTION_ADD_ARTICLE, 'edit' => Main::ACTION_UPDATE_ARTICLE,
		'view'    => Main::ACTION_GET_ARTICLE, 'delete' => Main::ACTION_DELETE_ARTICLE,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 *
	 */
	public function default_Action()
	{

		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm( 'article' );
		$this->view->setVar( 'search_form', $search_form );


		$grid = new UI_dataGrid();

		$grid->setIsPersistent( 'admin_classic_articles_list_grid' );

		$grid->addColumn( '_edit_', '' )->setAllowSort( false );
		$grid->addColumn( 'title', Tr::_( 'Title' ) );
		$grid->addColumn( 'date_time', Tr::_( 'Date and time' ) );


		$list = Article::getList();

		$grid->setData( $list );

		$this->view->setVar( 'grid', $grid );

		$this->render( 'default' );
	}

	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( $current_label = '' )
	{
		AdminUI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function add_Action()
	{
		$this->_setBreadcrumbNavigation( Tr::_( 'Create a new Article' ) );

		$article = new Article();

		$form = $article->getCommonForm();
		$form->getField( 'locale' )->setDefaultValue( Mvc::getCurrentLocale() );
		$form->getField( 'date_time' )->setDefaultValue( Data_DateTime::now() );

		if( $article->catchForm( $form ) ) {
			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );

			UI_messages::success(
				Tr::_( 'Article <b>%TITLE%</b> has been created', [ 'TITLE' => $article->getTitle() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $article->getId() ) );
		}



		$this->view->setVar( 'btn_label', Tr::_( 'ADD' ) );
		$this->view->setVar( 'has_access', true );
		$this->view->setVar( 'form', $form );

		$this->render( 'edit' );
	}

	/**
	 *
	 * @return Controller_Admin_Main_Router
	 */
	public function getControllerRouter()
	{
		return $this->module_instance->getAdminControllerRouter();
	}

	/**
	 *
	 */
	public function edit_Action()
	{

		/**
		 * @var Article $article
		 */
		$article = $this->getActionParameterValue( 'article' );

		$this->_setBreadcrumbNavigation( Tr::_( 'Edit article <b>%TITLE%</b>', [ 'TITLE' => $article->getTitle() ] ) );


		$form = $article->getCommonForm();

		if( $article->catchForm( $form ) ) {
			$article->save();

			$this->logAllowedAction( 'Article updated', $article->getId(), $article->getTitle(), $article );

			UI_messages::success(
				Tr::_( 'Article <b>%TITLE%</b> has been updated', [ 'TITLE' => $article->getTitle() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $article->getId() ) );
		}

		$this->view->setVar( 'btn_label', Tr::_( 'SAVE' ) );

		$this->view->setVar( 'form', $form );

		$this->render( 'edit' );
	}

	/**
	 *
	 */
	public function view_Action()
	{

		/**
		 * @var Article $article
		 */
		$article = $this->getActionParameterValue( 'article' );

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Article detail <b>%TITLE%</b>', [ 'TITLE' => $article->getTitle() ] )
		);

		Navigation_Breadcrumb::addURL( $article->getTitle() );


		$form = $article->getCommonForm();
		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );

		$this->render( 'edit' );
	}

	/**
	 *
	 */
	public function delete_action()
	{

		/**
		 * @var Article $article
		 */
		$article = $this->getActionParameterValue( 'article' );

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Delete article <b>%TITLE%</b>', [ 'TITLE' => $article->getTitle() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {

			$article->delete();

			$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

			UI_messages::info( Tr::_( 'Article <b>%TITLE%</b> has been deleted', [ 'TITLE' => $article->getTitle() ] ) );


			Http_Headers::movedTemporary( Mvc::getCurrentPage()->getURI() );
		}


		$this->view->setVar( 'article', $article );

		$this->render( 'delete-confirm' );
	}


}