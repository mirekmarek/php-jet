<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\REST\Articles;

use Jet\Logger;
use JetApplication\Content_Article;

use Jet\MVC_Controller_REST;
use Jet\MVC_Controller_REST_Router;

/**
 *
 */
class Controller_Main extends MVC_Controller_REST
{

	/**
	 * @var ?Content_Article
	 */
	protected ?Content_Article $article = null;

	/**
	 * @return MVC_Controller_REST_Router
	 */
	public function getControllerRouter(): MVC_Controller_REST_Router
	{
		$router = new MVC_Controller_REST_Router(
			$this,
			[
				'get'    => Main::ACTION_GET,
				'list'   => Main::ACTION_GET,
				'add'    => Main::ACTION_ADD,
				'update' => Main::ACTION_UPDATE,
				'delete' => Main::ACTION_DELETE,
			]
		);

		$router
			->setPreparer( function( $path ) {
				if(
					($id = $path) &&
					!($this->article = Content_Article::get( $id ))
				) {
					$this->responseUnknownItem( $id );
					return false;
				}

				return true;
			} )
			->setResolverGet( function() {
				return $this->article ? 'get' : 'list';
			} )
			->setResolverPost( function() {
				if( $this->article ) {
					return false;
				}
				return 'add';
			} )
			->setResolverPut( function() {
				if( !$this->article ) {
					return false;
				}
				return 'update';
			} )
			->setResolverDelete( function() {
				if( !$this->article ) {
					return false;
				}
				return 'delete';
			} );

		return $router;
	}

	/**
	 *
	 */
	public function get_Action(): void
	{
		$this->responseData( $this->article );
	}

	/**
	 *
	 */
	public function list_Action(): void
	{
		$this->responseData(
			$this->handleDataPagination(
				$this->handleOrderBy(
					Content_Article::getList(),
					[
						'title'     => 'article_localized.title',
						'date_time' => 'article.date_time'
					]
				)
			)
		);
	}

	/**
	 *
	 */
	public function add_Action(): void
	{
		$article = new Content_Article();

		$form = $article->getEditForm();

		$data = $this->getRequestData();

		$form->catchInput( $data, true );

		if( $form->validate() ) {
			$form->catchFieldValues();

			$article->save();

			Logger::success(
				event: 'article_created',
				event_message: 'Article created',
				context_object_id: $article->getId(),
				context_object_name: $article->getTitle(),
				context_object_data: $article
			);

			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getValidationErrors() );
		}

	}

	/**
	 *
	 */
	public function update_Action(): void
	{
		$article = $this->article;

		$form = $article->getEditForm();

		$form->catchInput( $this->getRequestData(), true );

		if( $form->validate() ) {

			$form->catchFieldValues();

			$article->save();

			Logger::success(
				event: 'article_updated',
				event_message: 'Article updated',
				context_object_id: $article->getId(),
				context_object_name: $article->getTitle(),
				context_object_data: $article
			);

			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getValidationErrors() );
		}

	}

	/**
	 *
	 */
	public function delete_Action(): void
	{
		$article = $this->article;

		$article->delete();

		Logger::success(
			event: 'article_deleted',
			event_message: 'Article deleted',
			context_object_id: $article->getId(),
			context_object_name: $article->getTitle(),
			context_object_data: $article
		);

		$this->responseOK();

	}
}