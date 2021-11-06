<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Admin;

use JetApplication\Content_Article;

use Jet\Mvc_Controller_Default;
use Jet\Mvc;

use Jet\UI_messages;

use Jet\Data_DateTime;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Navigation_Breadcrumb;
use Jet\Mvc_Controller_Router_AddEditDelete;

use JetApplicationModule\UI\Admin\Main as UI_module;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 * @var ?Mvc_Controller_Router_AddEditDelete
	 */
	protected ?Mvc_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?Content_Article
	 */
	protected ?Content_Article $article = null;

	/**
	 *
	 * @return Mvc_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter(): Mvc_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router_AddEditDelete(
				$this,
				function( $id ) {
					return (bool)($this->article = Content_Article::get( $id ));
				},
				[
					'listing' => Main::ACTION_GET_ARTICLE,
					'view'    => Main::ACTION_GET_ARTICLE,
					'add'     => Main::ACTION_ADD_ARTICLE,
					'edit'    => Main::ACTION_UPDATE_ARTICLE,
					'delete'  => Main::ACTION_DELETE_ARTICLE,
				]
			);

			$this->router->action( 'delete' )
				->setResolver( function() {
					return Http_Request::GET()->getString( 'action' ) == 'delete';
				} )
				->setURICreator( function() {
					return Http_Request::currentURI( ['action' => 'delete'] );
				} );
		}

		return $this->router;
	}

	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( string $current_label = '' ): void
	{
		UI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function listing_Action(): void
	{
		$this->_setBreadcrumbNavigation();

		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->filter_getForm() );
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}

	/**
	 *
	 */
	public function add_Action(): void
	{
		$this->_setBreadcrumbNavigation( Tr::_( 'Create a new Article' ) );

		$article = new Content_Article();

		$form = $article->getAddForm();

		$form->getField( 'date_time' )->setDefaultValue( Data_DateTime::now() );

		if( $article->catchAddForm() ) {
			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );

			UI_messages::success(
				Tr::_( 'Article <b>%TITLE%</b> has been created', ['TITLE' => $article->getTitle()] )
			);

			Http_Headers::reload( ['id' => $article->getId()], ['action'] );
		}


		$this->view->setVar( 'form', $form );

		$this->output( 'edit' );
	}

	/**
	 *
	 */
	public function edit_Action(): void
	{
		$article = $this->article;

		$this->_setBreadcrumbNavigation( Tr::_( 'Edit article <b>%TITLE%</b>', ['TITLE' => $article->getTitle()] ) );


		$form = $article->getEditForm();

		if( $article->catchEditForm() ) {
			$article->save();

			$this->logAllowedAction( 'Article updated', $article->getId(), $article->getTitle(), $article );

			UI_messages::success(
				Tr::_( 'Article <b>%TITLE%</b> has been updated', ['TITLE' => $article->getTitle()] )
			);

			Http_Headers::reload();
		}


		$this->view->setVar( 'form', $form );

		$this->output( 'edit' );
	}

	/**
	 *
	 */
	public function view_Action(): void
	{
		$article = $this->article;

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Article detail <b>%TITLE%</b>', ['TITLE' => $article->getTitle()] )
		);

		Navigation_Breadcrumb::addURL( $article->getTitle() );


		$form = $article->getCommonForm();
		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );

		$this->output( 'edit' );
	}

	/**
	 *
	 */
	public function delete_action(): void
	{
		$POST = Http_Request::POST();

		if( is_array( $ids = $POST->getRaw( 'selected' ) ) ) {
			foreach( $ids as $id ) {
				$article = Content_Article::get( $id );

				if( !$article ) {
					continue;
				}

				$article->delete();

				$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

				UI_messages::warning(
					Tr::_( 'Article <b>%TITLE%</b> has been deleted', ['TITLE' => $article->getTitle()] )
				);
			}
		}

		Http_Headers::movedTemporary( Mvc::getPage( Main::ADMIN_MAIN_PAGE )->getURLPath() );
	}


}