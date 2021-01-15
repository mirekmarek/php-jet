<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Content\Articles;

use Jet\Mvc_Controller_REST;
use Jet\Mvc_Controller_REST_Router;

/**
 *
 */
class Controller_REST extends Mvc_Controller_REST
{

	/**
	 * @var ?Article
	 */
	protected ?Article $article = null;

	/**
	 * @return Mvc_Controller_REST_Router
	 */
	public function getControllerRouter(): Mvc_Controller_REST_Router
	{
		$router = new Mvc_Controller_REST_Router(
			$this,
			[
				'get'    => Main::ACTION_GET_ARTICLE,
				'list'   => Main::ACTION_GET_ARTICLE,
				'add'    => Main::ACTION_ADD_ARTICLE,
				'update' => Main::ACTION_UPDATE_ARTICLE,
				'delete' => Main::ACTION_DELETE_ARTICLE,
			]
		);

		$router
			->setPreparer( function( $path ) {
				if(
					($id = $path) &&
					!($this->article = Article::get( $id ))
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

		/** @noinspection PhpParamsInspection */
		$this->responseData(
			$this->handleDataPagination(
				$this->handleOrderBy(
					Article::getList(),
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
		$article = new Article();

		$form = $article->getEditForm();

		$data = $this->getRequestData();

		$form->catchInput( $data, true );

		if( $form->validate() ) {
			$form->catchData();

			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );
			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
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

			$form->catchData();

			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );
			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}

	}

	/**
	 *
	 */
	public function delete_Action(): void
	{
		$article = $this->article;

		$article->delete();
		$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

		$this->responseOK();

	}
}