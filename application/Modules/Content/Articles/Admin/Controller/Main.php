<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Admin;

use Jet\Logger;
use JetApplication\Content_Article;

use Jet\MVC_Controller_Default;
use Jet\MVC;

use Jet\UI_messages;

use Jet\Data_DateTime;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Navigation_Breadcrumb;
use Jet\MVC_Controller_Router_AddEditDelete;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 * @var ?MVC_Controller_Router_AddEditDelete
	 */
	protected ?MVC_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?Content_Article
	 */
	protected ?Content_Article $article = null;

	/**
	 *
	 * @return MVC_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter(): MVC_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new MVC_Controller_Router_AddEditDelete(
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
	 *
	 */
	public function listing_Action(): void
	{
		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->getFilterForm() );
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}

	/**
	 *
	 */
	public function add_Action(): void
	{
		Navigation_Breadcrumb::addURL( Tr::_( 'Create a new Article' ) );

		$article = new Content_Article();

		$form = $article->getAddForm();

		$form->getField( 'date_time' )->setDefaultValue( Data_DateTime::now() );

		if( $article->catchAddForm() ) {
			$article->save();

			Logger::success(
				event: 'article_created',
				event_message: 'Article created',
				context_object_id: $article->getId(),
				context_object_name: $article->getTitle(),
				context_object_data: $article
			);

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
		
		Navigation_Breadcrumb::addURL( Tr::_( 'Edit article <b>%TITLE%</b>', ['TITLE' => $article->getTitle()] ) );


		$form = $article->getEditForm();

		if( $article->catchEditForm() ) {
			$article->save();

			Logger::success(
				event: 'article_updated',
				event_message: 'Article updated',
				context_object_id: $article->getId(),
				context_object_name: $article->getTitle(),
				context_object_data: $article
			);

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
		
		Navigation_Breadcrumb::addURL(
			Tr::_( 'Article detail <b>%TITLE%</b>', ['TITLE' => $article->getTitle()] )
		);


		$form = $article->createForm('view_article');
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

				Logger::success(
					event: 'article_deleted',
					event_message: 'Article deleted',
					context_object_id: $article->getId(),
					context_object_name: $article->getTitle(),
					context_object_data: $article
				);


				UI_messages::warning(
					Tr::_( 'Article <b>%TITLE%</b> has been deleted', ['TITLE' => $article->getTitle()] )
				);
			}
		}

		Http_Headers::movedTemporary( MVC::getPage( Main::ADMIN_MAIN_PAGE )->getURLPath() );
	}


}