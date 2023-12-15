<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\Content\Articles;

use Jet\Factory_MVC;
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

	protected ?MVC_Controller_Router_AddEditDelete $router = null;

	protected ?Content_Article $article = null;
	
	protected ?Listing $listing = null;
	
	public function getControllerRouter(): MVC_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new MVC_Controller_Router_AddEditDelete(
				controller: $this,
				item_catcher: function( $id ) : bool {
					return (bool)($this->article = Content_Article::get( $id ));
				},
				actions_map: [
					'listing' => Main::ACTION_GET,
					'view'    => Main::ACTION_GET,
					'add'     => Main::ACTION_ADD,
					'edit'    => Main::ACTION_UPDATE,
					'delete'  => Main::ACTION_DELETE,
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
	
	protected function getListing() : Listing
	{
		if(!$this->listing) {
			$column_view = Factory_MVC::getViewInstance( $this->view->getScriptsDir().'list/column/' );
			$column_view->setController( $this );
			
			$filter_view = Factory_MVC::getViewInstance( $this->view->getScriptsDir().'list/filter/' );
			$filter_view->setController( $this );
			
			$this->listing = new Listing(
				column_view: $column_view,
				filter_view: $filter_view
			);
		}
		
		return $this->listing;
	}
	
	public function listing_Action(): void
	{
		$listing = $this->getListing();
		$listing->handle();
		
		$this->view->setVar( 'listing', $listing );

		$this->output( 'list' );
	}
	
	protected function handleListingOnDetail() : void
	{
		$listing = $this->getListing();
		$listing->handle();
		
		$list_uri = $listing->getURI();
		Navigation_Breadcrumb::getItems()[1]->setURL( $list_uri );
		$this->view->setVar( 'list_url', $list_uri );
	}
	
	public function add_Action(): void
	{
		$this->handleListingOnDetail();
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
		$this->handleListingOnDetail();
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
		$this->handleListingOnDetail();
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